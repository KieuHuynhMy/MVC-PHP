<?
    class Admin extends Connect{
        function index() {
            $changeView['main'] = 'home/main';
            $this->loadView('admin/index', $changeView);
        }
    }
?>