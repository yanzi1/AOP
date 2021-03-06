--TEST--
aop_add_after_returning triggers only when functions / methods ends properly
--FILE--
<?php
function doStuff () {
   echo "doStuff";
}
function doStuffException () {
   echo "doStuffException";
   throw new Exception('Exception doStuffException');
}

class Stuff {
   public function doStuff () {
      echo "Stuff->doStuff";
   }

   public function doStuffException () {
      echo "Stuff->doStuffException";
      throw new Exception('Exception doStuffException');
   }

   public static function doStuffStatic () {
      echo "Stuff::doStuffStatic";
   }

   public static function doStuffStaticException () {
      echo "Stuff::doStuffStaticException";
      throw new Exception('Exception doStuffStaticException');
   }
}

aop_add_after_throwing('doStuff*()', function() {
   echo "[after]";
});
aop_add_after_throwing('Stuff->doStuff*()', function() {
   echo "[after]";
});

doStuff();
try {
   doStuffException();
} catch (Exception $e) {
   echo "[caught]";
}
$stuff = new Stuff();

$stuff->doStuff();
try {
   $stuff->doStuffException();
} catch (Exception $e) {
   echo "[caught]";
}
Stuff::doStuffStatic();
try {
   Stuff::doStuffStaticException();
} catch (Exception $e) {
   echo "[caught]";
}
--EXPECT--
doStuffdoStuffException[after][caught]Stuff->doStuffStuff->doStuffException[after][caught]Stuff::doStuffStaticStuff::doStuffStaticException[after][caught]
