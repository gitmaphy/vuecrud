<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Person extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        //Get the current UNIX timestamp.
        $now = time();
        //Get the timestamp of the person's date of birth.
        $dob = strtotime($this->birthday);
        //Calculate the difference between the two timestamps.
        $difference = $now - $dob;
        //There are 31556926 seconds in a year.
        $age = floor($difference / 31556926);
        //Print it out.
        

        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'age' => $age,
        ];
    }

    public function with($request) {
        return [
            'version' => '1.0.0.0',
            'author_url' => 'https://www.dropbox.com/s/zoakuvhz5aypze7/My%20Resume%20Web%20updated.pdf?dl=0'
        ];
    }
}
