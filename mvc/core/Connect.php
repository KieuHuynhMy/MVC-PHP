<?
    class Connect {
        function loadView($generalView, $changeView = []) {
            require_once './mvc/views/'.$generalView.'.php';
        }
    }
?>