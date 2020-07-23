<?
    class Connect{
        function loadView($generalView, $changeView = []) {
            require_once './mvc/views/'.$generalView.'.php';
        }

        function loadModel($model) {
            require_once './mvc/models/'.$model.'.php';
            return new $model;
        }
    }
?>