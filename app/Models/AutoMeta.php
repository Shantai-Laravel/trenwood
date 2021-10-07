<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoMeta extends Model
{
    protected $table = 'autometas';

    public function generateMeta($prodName, $catName, $field)
    {
    	$var1 = explode('#', $this->var1);
    	$var2 = explode('#', $this->var2);
    	$var3 = explode('#', $this->var3);
    	$var4 = explode('#', $this->var4);
    	$var5 = explode('#', $this->var5);
      $var6 = explode('#', $this->var6);
      $var7 = explode('#', $this->var7);
      $var8 = explode('#', $this->var8);
      $var9 = explode('#', $this->var9);
      $var10 = explode('#', $this->var10);
      $var11 = explode('#', $this->var11);
      $var12 = explode('#', $this->var12);
      $var13 = explode('#', $this->var13);
      $var14 = explode('#', $this->var14);
      $var15 = explode('#', $this->var15);

    	$$field = $this->$field;

    	$$field = str_replace('{{1}}', $var1[array_rand($var1)], $$field);
    	$$field = str_replace('{{2}}', $var2[array_rand($var2)], $$field);
    	$$field = str_replace('{{3}}', $var3[array_rand($var3)], $$field);
    	$$field = str_replace('{{4}}', $var4[array_rand($var4)], $$field);
    	$$field = str_replace('{{5}}', $var5[array_rand($var5)], $$field);
      $$field = str_replace('{{6}}', $var6[array_rand($var6)], $$field);
      $$field = str_replace('{{7}}', $var7[array_rand($var7)], $$field);
      $$field = str_replace('{{8}}', $var8[array_rand($var8)], $$field);
      $$field = str_replace('{{9}}', $var9[array_rand($var9)], $$field);
      $$field = str_replace('{{10}}', $var10[array_rand($var10)], $$field);
      $$field = str_replace('{{11}}', $var11[array_rand($var11)], $$field);
      $$field = str_replace('{{12}}', $var12[array_rand($var12)], $$field);
      $$field = str_replace('{{13}}', $var13[array_rand($var13)], $$field);
      $$field = str_replace('{{14}}', $var14[array_rand($var14)], $$field);
      $$field = str_replace('{{15}}', $var15[array_rand($var15)], $$field);
      $$field = str_replace('{{prodName}}', $prodName, $$field);
      $$field = str_replace('{{catName}}', $catName, $$field);
    	$$field = str_replace('{{', '', $$field);
    	$$field = str_replace('}}', '', $$field);
    	$$field = trim($$field);

    	return $$field;
    }
}
