<?php


namespace Source\Models\report;


use Source\Core\Model;
use Source\Core\Session;

class Access extends Model
{
    /**
     * Access constructor.
     */
    public function __construct()
    {
        parent::__construct("report_access", ['id'], ['users', 'views', 'pages']);
    }

    /**
     * @return Access
     * pt-br:
     */
    public function report(): Access
    {
        $find = $this->find("DATE(created_at) = DATE(now())")->fetch();
        $session = new Session();

        if(!$find){
            $this->users = 1;
            $this->views = 1;
            $this->pages = 1;

            setcookie("access", true, time() + 86400, "/");
            $session->set("access", true);

            $this->save();
            return $this;
        }

        return $this;
    }
}