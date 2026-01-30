<?php
// [...]
try {    
    echo calculateDivisor(10, 0);
} catch (\Throwable $t) {
    echo 'Error or Exception caught: ', $t->getMessage();
}


try{

    echo 'teste';
}
catch(\Error | \Exception $e){
    echo 'Error caught: ', $e->getMessage();
}
