<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Admin model funtion [Start]
        /**
         * Get subject based on generation , term and class;
         * @param $generationId 
         * @param $termId 
         * @param $classId 
         * @return object
         */
        public static function getSubjectReport($generationId, $termId, $classId){
            $respond = (object)[];
            $generation   = Generation::findOrFail($generationId);
            $term         = Term::findOrFail($termId);
            $class        = Classe::findOrFail($classId);
            $subject      = $class->subjects;

            $respond->generation  = $generation->name;
            $respond->term        = $term->name;
            $respond->class       = $class->name;
            $respond->subject     = $subject;
            
            return $respond;
        }
    // Admin model funtion [Start]

}
