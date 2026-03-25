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

## Wersjonowanie logiki

Poza wyżej wspomnianym routingiem, wersjonowanie zastosowano również na poziomie struktury plików wewnątrz katalogu `app/`. Pozwala to na całkowitą izolację zmian – modyfikacja walidacji czy formatu danych w `V2` nie wpłynie na stabilność działającej już wersji `V1`.

W związku z tym, każda wersja API posiada własne zestawy klas w następujących lokalizacjach:

**1. Controllers: `app/Http/Controllers/Api/V1/`**
- Logika sterująca daną wersją endpointów.

**2. Requests: `app/Http/Requests/Api/V1/`**
- Dedykowane klasy walidacji (np. `StoreUserRequest.php`). Dzięki temu zmiana reguł (np. dodanie wymaganego pola) w nowej wersji nie zepsuje formularzy w starszej wersji frontendu.

**3. Resources: `app/Http/Resources/V1/`**
- Sposób transformacji danych (Eloquent -> JSON). Pozwala to na swobodne zmienianie nazw kluczy w JSONie lub dodawanie nowych pól bez ryzyka błędu.

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

## Zabezpieczenie Model::shouldBeStrict()

W pliku `app/Providers/AppServiceProvider` włączona jest opcja `Model::shouldBeStrict()`. W dużym skrócie, opcja ta powoduje:

**1.** Brak lazy loading - jeśli w kodzie pojawi się eager loading (problem N+1), aplikacja rzuci błędem w trybie dev.

**2.** W przypadku próby zapisu pola, którego nie ma w `fillable` modelu, aplikacja rzuci wyjątek.

**3.** Aplikacja rzuci błąd w przypadku próby odczytu kolumny, która nie została pobrana w zapytaniu lub nie istnieje.

Więcej informacji z przykładami: https://laravel-news.com/shouldbestrict

## Konwencja commitów

Podczas tworzenia projektu zastosowano standard Conventional Commits, który pomaga utrzymać porządek w historii zmian. Nie trzeba się do niej stosować, najważniejsze, żeby treść commitu był czytelna i mówiła, co zostało zmienione. Gdyby jednak ktoś był zainteresowany tym standardem, to więcej informacji można znaleźć tutaj:
- https://gist.github.com/qoomon/5dfcdf8eec66a051ecd85625518cfd13
- https://gist.github.com/JohnnyWalkerDigital/7207004e8efd79751dbf55ece0420ef2
