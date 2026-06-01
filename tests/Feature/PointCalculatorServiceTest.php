<?php

namespace Tests\Feature;

use App\Enums\QualificationCategoryEnum;
use App\Filament\Resources\Qualifications\Pages\CreateQualification;
use App\Models\Pivots\RecruitmentApplication;
use App\Models\Pivots\RequirementGroupQualification;
use App\Models\Qualification;
use App\Services\PointCalculatorService;
use App\Models\RequirementGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Recruitment;
use App\Models\QualificationScore;
use App\Models\UserCertificate;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;

class PointCalculatorServiceTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /** @test */
    public function test_calculateSimple(): void
    {
        //Recruitment
        $recruitment = Recruitment::factory()->create();

        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 3,
            'recruitment_id' => $recruitment
        ]);

        $qualifications = Qualification::factory()
        ->count(3)
        ->sequence(
            ['name' => 'Matematyka'],
            ['name' => 'J. Polski'],
            ['name' => 'J. Angielski']
        )
        ->create(
            ['qualification_category_id' => QualificationCategoryEnum::MATURA_GRADE->id()]
        );

        $requirements = RequirementGroupQualification::factory()
        ->count(3)
        ->sequence(
            ['qualification_id' => $qualifications[0]],
            ['qualification_id' => $qualifications[1]],
            ['qualification_id' => $qualifications[2]],
        )
        ->create([
            'requirement_group_id' => $requirementGroup,
            'weight' => 1
        ]);

        //User and application

        //application
        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );
        //user has been created automatically
        $user = $recruitmentApplication->application->user;
        //Finnaly add scores with perfect value
        $scores = QualificationScore::factory()
        ->count(3)
        ->sequence(
            ['qualification_id' => $qualifications[0]],
            ['qualification_id' => $qualifications[1]],
            ['qualification_id' => $qualifications[2]]
        )
        ->for(UserCertificate::factory()->create(['user_id' => $user]))
        ->create(
            ['value' => 100]
        );

        $val = PointCalculatorService::calculate($recruitmentApplication);

        //3 exams * 100 should be equal to 300
        assertEquals(300, $val, '');
    }

    public function test_multipleCerts(): void {
        $recruitment = Recruitment::factory()->create();
        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 3,
            'recruitment_id' => $recruitment
        ]);

        $mat = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $pol = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $ang = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);

        //scores
        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );

        $user = $recruitmentApplication->application->user;
        $cert1 = UserCertificate::factory()->create(['user_id' => $user]);
        $cert2 = UserCertificate::factory()->create(['user_id' => $user]);
        $cert3 = UserCertificate::factory()->create(['user_id' => $user]);

        $this->addScore($cert1, $mat, 29);
        $this->addScore($cert1, $pol, 30);
        $this->addScore($cert1, $ang, 30);

        $this->addScore($cert2, $mat, 50);
        $this->addScore($cert2, $pol, 50);
        $this->addScore($cert2, $ang, 50);

        $this->addScore($cert3, $mat, 20);
        $this->addScore($cert3, $pol, 20);
        $this->addScore($cert3, $ang, 20);

        $val = PointCalculatorService::calculate($recruitmentApplication);
        assertEquals(150, $val, '');
    }

    public function test_multipleGroups(): void {
        $recruitment = Recruitment::factory()->create();
        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 3,
            'recruitment_id' => $recruitment
        ]);
        //all exams worth twice as much
        $maturaExt = RequirementGroup::factory()->create([
            'weight' => 2,
            'qualifications_count' => 3,
            'recruitment_id' => $recruitment
        ]);

        $mat = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $pol = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $ang = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);

        $matExt = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_EXT_GRADE, $maturaExt, 1);
        $polExt = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_EXT_GRADE, $maturaExt, 1);
        $angExt = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_EXT_GRADE, $maturaExt, 1);
        //scores
        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );

        $user = $recruitmentApplication->application->user;
        $cert1 = UserCertificate::factory()->create(['user_id' => $user]);

        $this->addScore($cert1, $mat, 1);
        $this->addScore($cert1, $pol, 1);
        $this->addScore($cert1, $ang, 1);

        $this->addScore($cert1, $matExt, 1);
        $this->addScore($cert1, $polExt, 1);
        $this->addScore($cert1, $angExt, 1);

        $val = PointCalculatorService::calculate($recruitmentApplication);
        assertEquals(9, $val, '');
    }

    public function test_differentWeights(): void {
        $recruitment = Recruitment::factory()->create();
        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 3,
            'recruitment_id' => $recruitment
        ]);

        //Math worth twice as much
        $mat = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 2);
        $pol = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $ang = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);

        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );

        $user = $recruitmentApplication->application->user;
        $cert1 = UserCertificate::factory()->create(['user_id' => $user]);

        $this->addScore($cert1, $mat, 1);
        $this->addScore($cert1, $pol, 1);
        $this->addScore($cert1, $ang, 1);

        $val = PointCalculatorService::calculate($recruitmentApplication);
        assertEquals(4, $val, '');
    }

    public function test_qualificationCount(): void {
        $recruitment = Recruitment::factory()->create();
        //only two most valuable scores count towards the recruitment
        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 2,
            'recruitment_id' => $recruitment
        ]);

        //Math worth twice as much
        $mat = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 2);
        $pol = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $ang = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);

        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );

        $user = $recruitmentApplication->application->user;
        $cert1 = UserCertificate::factory()->create(['user_id' => $user]);

        $this->addScore($cert1, $mat, 3);
        $this->addScore($cert1, $pol, 2);
        $this->addScore($cert1, $ang, 2);

        $val = PointCalculatorService::calculate($recruitmentApplication);
        //3 * 2 + 2 * 1 = 8
        assertEquals(8, $val, '');
    }

     public function test_unusedScore(): void {
        $recruitment = Recruitment::factory()->create();
        //only two most valuable scores count towards the recruitment
        $requirementGroup = RequirementGroup::factory()->create([
            'weight' => 1,
            'qualifications_count' => 2,
            'recruitment_id' => $recruitment
        ]);

        //Math worth twice as much
        $mat = $this->createQualification("Matematyka", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 2);
        $pol = $this->createQualification("Polski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $ang = $this->createQualification("Angielski", QualificationCategoryEnum::MATURA_GRADE, $requirementGroup, 1);
        $chem = $this->createQualification("Chemia", QualificationCategoryEnum::MATURA_EXT_GRADE, $requirementGroup, false);

        $recruitmentApplication = RecruitmentApplication::factory()->create(
            ['recruitment_id' => $recruitment]
        );

        $user = $recruitmentApplication->application->user;
        $cert1 = UserCertificate::factory()->create(['user_id' => $user]);

        $this->addScore($cert1, $mat, 3);
        $this->addScore($cert1, $pol, 2);
        $this->addScore($cert1, $ang, 2);
        $this->addScore($cert1, $chem, 2);

        $val = PointCalculatorService::calculate($recruitmentApplication);
        //3 * 2 + 2 * 1 = 8
        assertEquals(8, $val, '');
    }


    private function createQualification(String $name, QualificationCategoryEnum $category, RequirementGroup $rqgroup, int $weight, bool $addRequirement = true):Qualification {
        $qualification = Qualification::factory()
            ->create([
                'name' => $name,
                'qualification_category_id' => $category->id()
                ]);

        //create corresponding requirement
        if($addRequirement) {
            RequirementGroupQualification::factory()
            ->create([
                'requirement_group_id' => $rqgroup,
                'qualification_id' => $qualification,
                'weight' => $weight
            ]);
        }
        return $qualification; 
   }

   private function addScore(UserCertificate $cert, Qualification $qualification, int $score) {
    QualificationScore::factory()
        ->for($cert)
        ->create([
            'qualification_id' => $qualification,
            'value' => $score
            ]);
   }
}