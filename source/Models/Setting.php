<?php


namespace Source\Models;


use Faker\Factory;
use Source\Core\Model;

class Setting extends Model
{
    public function __construct()
    {
        parent::__construct("settings", ["id"], ["project_name", "description", "about", "phone"]);
    }

    public function start(): Setting
    {
        if(!$this->find("id = :id", "id=1")->count()){
            $faker = Factory::create();

            $this->project_name = "Meu site";
            $this->description = $faker->text(200);
            $this->about = $faker->text(400);
            $this->phone = "5155555555";
            $this->save();
        }
        return $this;
    }

}