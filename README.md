# IRK - Backend (Laravel API + FilamentPHP)

## Instalacja i uruchomienie

**1. Przygotowanie systemu Windows (opcjonalne, jeśli Docker jest już zainstalowany)**
- Zainstalować **[Docker Desktop](https://www.docker.com/products/docker-desktop/)**.
- Upewnić się, że w ustawieniach Dockera (General) zaznaczona jest opcja **"Use the WSL 2 based engine"**.
- W sekcji "Settings -> Resources -> WSL Integration" włączyć integrację dla swojej dystrybucji (np. Ubuntu), jeśli jest wykorzystywana.

**2. Pobranie projektu i konfiguracja**

Otworzyć terminal (najlepiej WSL2) i wykonać poniższe kroki:

```bash
# Klonowanie projektu - zamiast irk-backend wpisanego po adresie repozytorium można podać ścieżkę na dysku do zapisu projektu.
git clone https://github.com/2moki/irk-backend irk-backend

# Przejście do katalogu
cd <nazwa-folderu / ścieżka>

# Kopiowanie pliku środowiskowego
cp .env.example .env
```

**Ważne:** Należy otworzyć plik .env w edytorze i upewnić się, że sekcja dotycząca bazy danych zawiera poniższe dane (szczególną uwagę zwrócić na host, nazwę użytkownika i hasło). Jeśli dane będą niezgodne, należy je podmienić na poniższe:
```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=irk_backend
DB_USERNAME=sail
DB_PASSWORD=password
```

**3. Instalacja zależności**

Jeśli w systemie nie ma zainstalowanego PHP oraz Composera, można użyć tymczasowego kontenera PHP 8.4, który wykona instalację samodzielnie. W katalogu projektu należy uruchomić następującą komendę:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

Jeśli w systemie jest zainstalowane PHP oraz Composer, użycie poniższej komendy powinno być wystarczające. Niemniej nawet mając zainstalowany PHP oraz Composer, powyższe rozwiązanie także zadziała. W przypadku użycia powyższej komendy docker, pominąć poniższą.
```bash
composer install
```

**4. Uruchomienie aplikacji**

```bash
# Uruchomienie kontenerów w tle (PHP 8.5 + PostgreSQL + Mailpit)
./vendor/bin/sail up -d

# Wygenerowanie klucza aplikacji (wymagane przy pierwszym starcie)
./vendor/bin/sail artisan key:generate

# Uruchomienie migracji bazy danych wraz z przykładowymi danymi (Seed)
./vendor/bin/sail artisan migrate --seed
```

#### Alias do komendy ./vendor/bin/sail (opcjonalne)
Aby nie wpisywać za każdym razem pełnej ścieżki `./vendor/bin/sail`, warto dodać alias.

**Dla WSL2 (bash / zsh):**
Należy dodać poniższą linię do pliku `~/.bashrc` lub `~/.zshrc` (np. `nano ~/.bashrc`):
```bash
alias sail="./vendor/bin/sail"
```

Po dodaniu należy przeładować terminal komendą `source ~/.bashrc`. Od tej pory wystarczy wpisywać `sail up -d`, `sail artisan` itd. zamiast pełnej ścieżki do sail (`./vendor/bin/sail`).

<br>

## Architektura routingu

W projekcie zastosowano podział routingów ze względu na ich przeznaczenie oraz wersjonowanie API. Każdy plik wejściowy (`routes.php`) pełni rolę punktu dostępowego dla danej warstwy aplikacji.

**1. API - Katalog `routes/api/`**

Stosowane jest tutaj wersjonowanie (obecnie v1) oraz podział na moduły aplikacji. Nowe endpointy należy dodawać w dedykowanych plikach w `routes/api/v1/`. Dzięki temu `users.php` zawiera tylko logikę użytkowników, a nie 500 linii kodu wszystkiego.
Wersjonowanie pozwala na wprowadzanie zmian w strukturze danych bez psucia działającego już frontendu, poprzez równoległe utrzymywanie różnych wariantów endpointów. Dzięki temu można swobodnie rozwijać nową logikę w `v2`, zapewniając dalsze działanie dla kodu korzystającego z `v1`.

**Plik wejściowy:** `routes/api/routes.php`

Struktura pliku:
```php
Route::middleware(['throttle:api'])
    ->prefix('v1')
    ->as('v1:')
    ->group(function (): void {
        // Rejestracja modułów z podfolderu v1/
        Route::prefix('users')
            ->as('users:')
            ->group(base_path('routes/api/v1/users.php'));
    });
```

Struktura przykładowego modułu users (`routes/api/v1/users.php`):
```php
<?php  
  
declare(strict_types=1);  
  
use App\Http\Controllers\Controller;  
  
Route::get('/', [Controller::class, 'index'])->name('index');  
Route::post('/', [Controller::class, 'store'])->name('store');  
Route::get('{user}', [Controller::class, 'show'])->name('show');  
Route::patch('{user}', [Controller::class, 'update'])->name('update'); // mógłby być put lub oba
Route::delete('{user}', [Controller::class, 'destroy'])->name('destroy');
```

Dzięki zastosowaniu takiego podejścia i nazewnictwa, struktura routingu będzie dużo czystsza i czytelniejsza, a stosowanie odpowiednich nazw i prefixów pozwoli na łatwe odczytanie route:list. Ponadto środowisko będzie gotowe na ewentualne utworzenie drugiej wersji API w przyszłości.

![Routing](https://i.imgur.com/vknoZTq.png)

**2. WEB - Katalog `routes/web/`**

To warstwa obsługująca zapytania bezpośrednio z przeglądarki. Wykorzystywana chociażby przez FilamentPHP czy Fortify.
**Plik wejściowy:** `routes/web/routes.php`

**3. Console - Katalog `routes/console/`**

Miejsce przeznaczone do definiowania komend Artisan oraz Schedule.
**Plik wejściowy:** `routes/console/routes.php`

<br>

## Wersjonowanie logiki

Poza wyżej wspomnianym routingiem, wersjonowanie zastosowano również na poziomie struktury plików wewnątrz katalogu `app/`. Pozwala to na całkowitą izolację zmian – modyfikacja walidacji czy formatu danych w `V2` nie wpłynie na stabilność działającej już wersji `V1`.

W związku z tym, każda wersja API posiada własne zestawy klas w następujących lokalizacjach:

**1. Controllers: `app/Http/Controllers/Api/V1/`**
- Logika sterująca daną wersją endpointów.

**2. Requests: `app/Http/Requests/Api/V1/`**
- Dedykowane klasy walidacji (np. `StoreUserRequest.php`). Dzięki temu zmiana reguł (np. dodanie wymaganego pola) w nowej wersji nie zepsuje formularzy w starszej wersji frontendu.

**3. Resources: `app/Http/Resources/V1/`**
- Sposób transformacji danych (Eloquent -> JSON). Pozwala to na swobodne zmienianie nazw kluczy w JSONie lub dodawanie nowych pól bez ryzyka błędu.

<br>

## Dokumentacja API (Scramble)

W projekcie zainstalowane jest Scramble - narzędzie, które automatycznie generuje dokumentację API (OpenAPI/Swagger) na podstawie kodu. Nie trzeba ręcznie pisać plików JSON czy YAML - Scramble sam analizuje trasy, kontrolery i requesty. Dodatkowo w panelu wyświetla wszystkie reguły walidacji poszczególnych pól.

- Dostęp: http://localhost/docs/api
- Więcej informacji: https://scramble.dedoc.co

![Scramble](https://i.imgur.com/8WkLebB.png)

#### Ważne: Obsługa filtrów (Spatie QueryBuilder)

Darmowa wersja Scramble ma jedno małe ograniczenie: nie wykrywa automatycznie filtrów i sortowania z paczki Spatie QueryBuilder.

Aby rozwiązać ten problem, trzeba je ręcznie opisać w kontrolerze za pomocą atrybutu `#[QueryParameter]`. Dzięki temu pojawią się one jako pola do wpisania w interfejsie dokumentacji.

Przykład użycia w kontrolerze:
```php
use Dedoc\Scramble\Attributes\QueryParameter;

// ...

#[QueryParameter('filter[name]', description: 'Filtruj po nazwie', type: 'string')]
#[QueryParameter('sort', description: 'Sortowanie (np. -created_at)', type: 'string')]
public function index()
{
    return UserResource::collection(
        QueryBuilder::for(User::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['created_at'])
            ->get()
    );
}
```

<br>

## Autoryzacja i testowanie API (Środowisko lokalne)

Dostęp do chronionych zasobów API w celach testowych został zintegrowany z mechanizmem sesji oraz tokenów Bearer. Wybór metody zależy od używanego narzędzia:

- W celu wysłania zapytania logującego należy wywołać endpoint `POST /api/v1/dev-token`. W interfejsie Scramble należy przejść do `AuthToken -> dev-token`. Dane są uzupełnione domyślnie, więc wystarczy użyć przycisku **Send API Request**.
- W celu korzystania z dokumentacji Scramble nie trzeba ręcznie kopiować tokena do pola "Token" w oknie "Auth". Dzięki mechanizmowi `Auth::attempt` przeglądarka automatycznie zarządza sesją (Cookie), co pozwala na natychmiastowe testowanie chronionych zasobów bez dodatkowej konfiguracji.
- W celu konfiguracji narzędzi zewnętrznych (Postman / Yaak) należy skopiować token z otrzymanej odpowiedzi JSON i ustawić go ręcznie w nagłówku jako `Authorization: Bearer <token>`.

#### Ważna uwaga dotycząca SPA (Vue.js)

Uwierzytelnianie oparte na tokenach Bearer jest wykorzystywane tylko do testów manualnych. Aplikacja frontendowa (Vue.js) korzysta z domyślnego mechanizmu sesji i ciastek dostarczanego przez Laravel Sanctum. Podczas pracy z SPA nie ma potrzeby ręcznego generowania ani przesyłania tokenów w nagłówkach.

![AuthToken](https://i.imgur.com/ca4ANMh.png)

<br>

## Filtrowanie i Sortowanie (Spatie QueryBuilder)

Aby nie pisać ręcznie dziesiątek warunków `if ($request->has('filter'))` w każdym kontrolerze, zainstalowana została paczka Spatie QueryBuilder. Pozwala ona na błyskawiczne budowanie zapytań SQL bezpośrednio z parametrów w adresie URL.

#### Po co to jest?

Zamiast tworzyć osobne endpointy dla różnych widoków, wystarczy jeden, który obsłuży:
- Filtrowanie: `?filter[name]=Jan`
- Sortowanie: `?sort=-created_at` (minus oznacza malejąco)
- Dołączanie relacji (Eager Loading): `?include=posts,profile`
- Więcej informacji: https://spatie.be/docs/laravel-query-builder/v7/introduction

Przykład:
W kontrolerze definiuje się tylko to, na co pozwolono użytkownikowi. Jeśli czegoś nie ma na liście `allowed`, QueryBuilder to zignoruje.

```php
public function index()
{
    return QueryBuilder::for(User::class)
        ->allowedFilters(['name', 'email', 'status']) // Zezwolone filtry
        ->allowedSorts(['id', 'created_at']) // Zezwolone sortowanie
        ->allowedIncludes(['posts']) // Zezwolone relacje do dołączenia
        ->get();
}
```

<br>

## Panel administratora (FilamentPHP)

Panel admina znajduje się pod adresem `/admin` (np. http://localhost/admin).

<br>

## Jakość kodu

W celu zwiększenia jakości i spójności kodu, w projekcie zainstalowane są dwa narzędzia:

**1.** PHPStan (Larastan) - Analizator statyczny, który sprawdza typy i błędy przed uruchomieniem kodu.
   
- Wywołanie: `composer stan`
- Więcej informacji:
  - https://phpstan.org/documentation
  - https://github.com/larastan/larastan

**2.** Laravel Pint - Narzędzie do automatycznego formatowania stylu kodu (Style Fixer). Umożliwi nam to utrzymanie spójnego stylu kodu.
   
- Wywołanie: `composer pint`
- Więcej informacji: https://laravel.com/docs/13.x/pint

#### Ważne!
Przed każdym commitem warto uruchomić komendę `composer check`. To połączone polecenie, które najpierw sformatuje kod przy użyciu Laravel Pint, a następnie zweryfikuje przy pomocy PHPStan.

<br>

## Debugowanie i Monitoring

W projekcie zainstalowane są narzędzia, które ułatwiają podgląd tego, co dzieje się pod maską:

**1.** Laravel Telescope - Monitoruje requesty, zapytania SQL, eventy, maile i wiele więcej.  
- Dostęp: http://localhost/telescope
- Więcej informacji: https://laravel.com/docs/13.x/telescope

**2.** Log Viewer - Interfejs do przeglądania logów Laravela.
- Dostęp: http://localhost/log-viewer
- Więcej informacji: https://log-viewer.opcodes.io/docs/3.x

**3.** Mailpit - Lokalny serwer SMTP do testów wysyłki maili.
- Dostęp: http://localhost:8025
   
W celu skonfigurowania tej usługi, należy podmienić poniższe linijki w pliku .env:
```
MAIL_MAILER=log  
MAIL_HOST=127.0.0.1  
MAIL_PORT=2525
```
 na:
```
MAIL_MAILER=smtp   
MAIL_HOST=mailpit  
MAIL_PORT=1025
```

![Mailpit](https://i.imgur.com/u4t7jsW.png)

<br>

## Zabezpieczenie Model::shouldBeStrict()

W pliku `app/Providers/AppServiceProvider` włączona jest opcja `Model::shouldBeStrict()`. W dużym skrócie, opcja ta powoduje:

**1.** Brak lazy loading - jeśli w kodzie pojawi się eager loading (problem N+1), aplikacja rzuci błędem w trybie dev.

**2.** W przypadku próby zapisu pola, którego nie ma w `fillable` modelu, aplikacja rzuci wyjątek.

**3.** Aplikacja rzuci błąd w przypadku próby odczytu kolumny, która nie została pobrana w zapytaniu lub nie istnieje.

Więcej informacji z przykładami: https://laravel-news.com/shouldbestrict

<br>

## Konwencja commitów

Podczas tworzenia projektu zastosowano standard Conventional Commits, który pomaga utrzymać porządek w historii zmian. Nie trzeba się do niej stosować, najważniejsze, żeby treść commitu był czytelna i mówiła, co zostało zmienione. Gdyby jednak ktoś był zainteresowany tym standardem, to więcej informacji można znaleźć tutaj:
- https://gist.github.com/qoomon/5dfcdf8eec66a051ecd85625518cfd13
- https://gist.github.com/JohnnyWalkerDigital/7207004e8efd79751dbf55ece0420ef2
