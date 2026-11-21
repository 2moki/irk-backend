<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Pivots\RecruitmentApplication;
use App\Models\Pivots\RequirementGroupQualification;
use App\Models\QualificationScore;
use App\Models\RequirementGroup;
use App\Models\UserCertificate;

class PointCalculatorService
{
    /**
     * Calculates total points received in application
     *
     * @param RecruitmentApplication $application Student's application to a recruitment
     * @return int Total points received in application
     */
    public static function calculate(RecruitmentApplication $application): int
    {
        //Parent application
        $pappl = Application::query()->where('id', $application->application_id)->first();

        //requirements
        $reqgroups = RequirementGroup::query()->where('recruitment_id', $application->recruitment_id)->get();

        //certificates
        //Could return multiple certificates, need to go through all of them
        $userCertificats = UserCertificate::query()->where('user_id', $pappl->user_id)->get();
        dump('User name: ' . $pappl->user->id);

        //No certificates submitted
        if (count($userCertificats) === 0) {
            return -1;
        }

        //will contain sum of all scores for each cercificate
        $pointsTotal = [];

        //now going through all of them
        foreach ($userCertificats as $cert) {
            $points = 0;
            //Get related scores to cercificate
            $scores = QualificationScore::query()->where('user_certificate_id', $cert->id)->get();
            //Do not run calculations if cercificate does not have any scores (for some reason)
            if (count($scores) === 0) {
                $pointsTotal[$cert->id] = 0;
                continue;
            }

            //iterate through every requirementGroup
            foreach ($reqgroups as $rqgrp) {
                //fetch requirements from a group
                $requirements = RequirementGroupQualification::query()->with('qualification')->where('requirement_group_id', $rqgrp->id)->get();
                //will contain all scores from one group
                $groupScores = [];
                //iterate through requirements and search for corresponding score
                dump('Requirements found: ' . count($requirements));
                foreach ($requirements as $rq) {
                    foreach ($scores as $score) {
                        if ($rq->qualification_id === $score->qualification_id) {
                            //Multiply amount of points by weight of that requirement, add to score and break
                            // $points += $rq->weight * $score->value;
                            $groupScores[] = $rq->weight * $score->value * $rqgrp->weight;
                            break;
                        }
                    }
                }
                //sort received scores
                rsort($groupScores);
                //add to points top x qualifications
                for ($i = 0; $i < $rqgrp->qualifications_count; $i++) {
                    $points += $groupScores[$i];
                }
            }

            //Push to pointsTotal with key as cert id and value as total points from that certificate
            $pointsTotal[$cert->id] = $points;
        }

        $highest = 0;

        foreach ($pointsTotal as $pts) {
            if ($pts > $highest) {
                $highest = $pts;
            }
        }

        return $highest;
    }

}
