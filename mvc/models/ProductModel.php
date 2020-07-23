<?
    class ProductModel extends DB{
        protected $table = 'product';

        function productInsert($array = []) {
            return $this->insert($this->table, $array);
        }
    }
?>