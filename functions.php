<?php
  /* library */
  $exp = array(
    "negation"=>array(
      "bukan","tidak"
    ),
    "conjunction"=>array(
      "dan"
    ),
    "disjunction"=>array(
      "atau"
    ),
    "conditional"=>array(
      "jika","maka",",","mengakibatkan","hanya jika","syarat cukup","syarat perlu","bilamana"
    ),
    "biconditional"=>array(
      "jika","maka",",","dan jika","jika dan hanya jika","jika hanya jika","syarat perlu dan cukup","sebaliknya"
    )
  );

  $vnm = range("a", "z");
  $op = array("~","&","|","@",">","<");

  /* function */
  function lrtrim($data) {
    return rtrim(ltrim($data, " "), " ");
  }

  function compare($a, $b, $lvl) {
    $res = ($a == $b);
    if ($lvl == "H") {
      $res = ($a === $b);
    }
    return $res;
  }

  function destroy_empty_array($data) {
    foreach ($data as $key => $value) {
      $data[$key] = preg_replace( "/\r|\n/", "", $data[$key]);
      if (empty($value) || !isset($value) || $value == "" || $value == null || strlen($data[$key]) == 0) {
        unset($data[$key]);
      }
    }
    return $data;
  }

  function sort_charlen($data) {
    for ($i= 0 ;$i < count($data); $i++) {
      for ($j = $i; $j < count($data)-1; $j++) {
        if (strlen($data[$j]) > strlen($data[$j+1])) {
          $tmp = $data[$j];
          $data[$j] = $data[$j+1];
          $data[$j+1] = $tmp;
        }
      }
    }
    return $data;
  }

  function proposition_entered($data) {
    $res = false;
    for ($i = 1; $i <= 7; $i++) {
      if ($data["proposisi-" . $i . "-a"] != "" || !empty($data["proposisi-" . $i . "-a"])) {
        $res = true; break;
      }
    }
    return $res;
  }

  function proposition_count($data) {
    $max = 0;
    for ($i = 1; $i <= 7; $i++) {
      if ($data["proposisi-" . $i . "-a"] == "" || empty($data["proposisi-" . $i . "-a"])) {
        $max = $i-1; break;
      }
    }
    return $max;
  }

  function arrange_proposition($input, $type, $vnm, $var) {
    $res = "";
    if ($type == "desc") { //description
      $prop = explode(";", $input);
      if ($prop[2] != "") {
        if ($prop[2] == "blank") { //operator
          if ($prop[0] == "negative") {
            $prop[1] = "&not;(" . $prop[1] . ")";
          }
          $res = $prop[1]; //proposisi atomik
        }
        else {
          if ($prop[0] == "negative") {
            $prop[1] = "&not;(" . $prop[1] . ")";
          }
          if ($prop[3] == "negative") {
            $prop[4] = "&not;(" . $prop[4] . ")";
          }
          switch ($prop[2]) {
            case 'conjunction':
              $res = $prop[1] . " dan " . $prop[4];
            break;
            case 'disjunction':
              $res = $prop[1] . " atau " . $prop[4];
            break;
            case 'excdisjunction':
              $res = $prop[1] . " atau " . $prop[4];
            break;
            case 'conditional':
              $res = "Jika " . $prop[1] . " maka " . $prop[4];
            break;
            case 'biconditional':
              $res = $prop[1] . " jika dan hanya jika " . $prop[4];
            break;
            default:break;
          }
        }
      }

    }
    else {
      //default $type = "form/formula"
      $prop = explode(";", $input);
      if ($prop[2] != "") {
        if ($prop[2] == "blank") { //operator
          foreach ($var as $key => $value) {
            if ($value == $prop[1]) {
              $prop[1] = $vnm[$key]; //proposisi atomik
            }
          }
          if ($prop[0] == "negative") {
            $prop[1] = "~" . $prop[1];
          }
          $res = $prop[1];
        }
        else {
          foreach ($var as $key => $value) {
            if ($value == $prop[1]) {
              $prop[1] = $vnm[$key];
            }
          }
          if ($prop[0] == "negative") {
            $prop[1] = "~" . $prop[1];
          }

          foreach ($var as $key => $value) {
            if ($value == $prop[4]) {
              $prop[4] = $vnm[$key];
            }
          }
          if ($prop[3] == "negative") {
            $prop[4] = "~" . $prop[4];
          }
          $res = $prop[1];
          switch ($prop[2]) {
            case 'conjunction':
              $res .= "&";
            break;
            case 'disjunction':
              $res .= "|";
            break;
            case 'excdisjunction':
              $res .= "@";
            break;
            case 'conditional':
              $res .= ">";
            break;
            case 'biconditional':
              $res .= "<";
            break;
            default:break;
          }
          $res .= $prop[4];
        }
      }
    }
    return strtolower($res);
  }

  function rewrite_operator($op) {
    $res = "";
    switch ($op) {
      case '~':
        $res = "&not;";
      break;
      case '&':
        $res = "&and;";
      break;
      case '|':
        $res = "&or;";
      break;
      case '@':
        $res = "&oplus;";
      break;
      case '>':
        $res = "&rarr;";
      break;
      case '<':
        $res = "&harr;";
      break;
      default:break;
    }
    return $res;
  }

  function rewrite_proposition($prop, $op) {
    $prop = str_split($prop);
    $res = "";
    for ($i = 0; $i < count($prop); $i++) {
      foreach ($op as $key => $value) {
        if ($prop[$i] == $value) {
          $prop[$i] = rewrite_operator($prop[$i]);
          break;
        }
      }
      $res .= $prop[$i];
    }
    return $res;
  }

  function rewrite_value($bool) {
    return (($bool == 1)?"T":"F");
  }

  function show_argument($data, $max) {
    for ($i = 1; $i <= $max; $i++) {
      $tmp = $data["operator-binary-" . $i . "-a"] . ";" . $data["proposisi-" . $i . "-a"] . ";";
      $tmp .= $data["operator-majemuk-" . $i] . ";" . $data["operator-binary-" . $i . "-b"] . ";";
      $tmp .= $data["proposisi-" . $i . "-b"];
      $tmp = strtolower($tmp);
      echo ucfirst(arrange_proposition($tmp, "desc", "", "")) . ".<br />";
    }
  }

  function show_proposition($data, $max) {
    for ($i = 1; $i <= $max; $i++) {
      $tmp = $data["operator-binary-" . $i . "-a"] . ";" . $data["proposisi-" . $i . "-a"] . ";";
      $tmp .= $data["operator-majemuk-" . $i] . ";" . $data["operator-binary-" . $i . "-b"] . ";";
      $tmp .= $data["proposisi-" . $i . "-b"];
      $tmp = strtolower($tmp);
      if ($i == $max) {
        echo "<i>q = </i> ";
      }
      else {
        echo "<i>p" . $i . "</i> = ";
      }
      echo ucfirst(arrange_proposition($tmp, "desc", "", "")) . ".<br />";
    }
  }

  function get_variable($data, $max) {
    $var = array();
    for ($i = 1; $i <= $max; $i++) {
      if (!empty($data["proposisi-" . $i . "-a"]) && $data["proposisi-" . $i . "-a"] != "" && $data["proposisi-" . $i . "-a"] != " ") {
        array_push($var, strtolower($data["proposisi-" . $i . "-a"]));
      }
      if (!empty($data["proposisi-" . $i . "-b"]) && $data["proposisi-" . $i . "-b"] != "" && $data["proposisi-" . $i . "-b"] != " ") {
        array_push($var, strtolower($data["proposisi-" . $i . "-b"]));
      }
    }
    return array_values(array_unique($var));
  }

  function show_variable($var, $vnm) {
    for ($i = 0; $i < count($var); $i++) {
      echo $vnm[$i] . " = " . ucfirst($var[$i]) . "<br />";
    }
  }

  function get_formulation($data, $max, $vnm) {
    $form = array();
    for ($i = 1; $i <= $max; $i++) {
      $tmp = $data["operator-binary-" . $i . "-a"] . ";" . $data["proposisi-" . $i . "-a"] . ";";
      $tmp .= $data["operator-majemuk-" . $i] . ";" . $data["operator-binary-" . $i . "-b"] . ";";
      $tmp .= $data["proposisi-" . $i . "-b"];
      $tmp = strtolower($tmp);
      array_push($form, arrange_proposition($tmp, "form", $vnm, get_variable($data, $max)));
    }
    return array_values(array_unique($form));
  }

  function show_formulation($data, $op) {
    for ($i = 0; $i < count($data); $i++) {
      if ($i == count($data)-1) {
        echo "<hr style='margin-top:1px; margin-bottom:1px;' /><b style='font-size:14pt;'>&there4;</b> ";
      }
      echo rewrite_proposition($data[$i], $op) . "<br />";
    }
  }

  function get_operand($form, $pattern, $max) {
    $res = "";
    $prop = explode(";", $pattern);
    $data = str_split(lrtrim($form));
    if ($prop[2] != "") {
      if ($prop[2] == "blank") { //operator
        if ($prop[0] == "negative") {
          $res = $data[0] . $data[1] . ";";
        }
        else {
          $res .= $data[0] . ";";
        }
      }
      else {
        switch ($prop[2]) {
          case 'conjunction':
            $del = "&";
          break;
          case 'disjunction':
            $del = "|";
          break;
          case 'excdisjunction':
            $del = "@";
          break;
          case 'conditional':
            $del = ">";
          break;
          case 'biconditional':
            $del = "<";
          break;
          default:break;
        }
        $ab = explode($del, $form);//seperate by delimiter = operator
        for ($i = 0; $i < count($ab); $i++) {
          if (strlen($ab[$i]) == 2) {
            $tab = str_split($ab[$i]);
            $res = $ab[0] . $del . $ab[1] . ";";
            $res .= $ab[1] . ";";
          }
          else {
            $res .= $ab[0] . ";";
          }
        }
        $res .= $form . ";";

      }
    }
    return $res;
  }

  function get_all_operand($data, $form, $max, $vnm, $var) {
    $operand = "";
    $prms = "";
    for ($i = 0; $i < $var; $i++) {
      $operand .= $vnm[$i] . ";"; //take from var (a, b..)
    }

    for ($i = 0; $i < count($form); $i++) {
      $tmp = $data["operator-binary-" . ($i+1) . "-a"] . ";" . $data["proposisi-" . ($i+1) . "-a"] . ";";
      $tmp .= $data["operator-majemuk-" . ($i+1)] . ";" . $data["operator-binary-" . ($i+1) . "-b"] . ";";
      $tmp .= $data["proposisi-" . ($i+1) . "-b"];
      $tmp = strtolower($tmp);
      $operand .= get_operand($form[$i], $tmp, $max);
    }
    $operand = explode(";", $operand);

    for ($i = 0; $i < count($form) - 1; $i++) {
      $prms .= $form[$i];
      if ($i < count($form)-2) {
         $prms .= "&";
      }
      array_push($operand, $form[$i]);
    }
    $operand = array_values(array_unique(destroy_empty_array($operand)));
    sort($operand);
    $operand = sort_charlen($operand);
    array_push($operand, $prms);
    array_push($operand, $prms . ">" . $form[count($form)-1]); // p1, p2..pn -> q
    return $operand;
  }

  function base_truth_table($count) { //thank you stackoverflow
    if (1 === $count) {
      // true and false for the first variable
      return array(array(1), array(0)); //1 = true, 0 = false
    }
    // get 2 copies of the output for 1 less variable
    $trues = $falses = base_truth_table(--$count);
    for ($i = 0, $total = count($trues); $i < $total; $i++) {
        // the true copy gets a T added to each row
        array_unshift($trues[$i], 1);
        // and the false copy gets an F
        array_unshift($falses[$i], 0);
    }
    // combine the T and F copies to give this variable's output
    return array_merge($trues, $falses);
  }

  function count_operator($form, $op) {
    $res = 0;
    $form = str_split($form);
    foreach ($form as $key => $value) {
      $tmp = str_split($value);
      for ($q = 0; $q < count($op); $q++) {
        if ($value == $op[$q]) {
          $res++;
        }
      }
    }
    return $res;
  }

  function step_truth_table($operand, $table, $form, $var, $row, $op) {
    $max = count($operand);
    $min = count($var);
    array_shift($op);//remove ~(negation)
    $idx = 0;
    for ($j = $min; $j < $max-1; $j++) { //kolom
      if (strlen($operand[$j]) == 1) {
        //atomik positif, harusnya tidak pernah masuk statement ini, jika masuk = bug
      }
      else if (strlen($operand[$j]) == 2) {
        //atomik negatif, cari yg positifnya dlu
        $top = str_split($operand[$j]);
        foreach ($operand as $key => $value) {
          if ($value == $top[1]) { //formulasi (a, b..)
            $idx = $key;//ketemu yang positif
            break;
          }
        }
        for ($i = 0; $i < $row; $i++) { //reverse element from 0..n, 1->0, 0->1
          if ($table[$i][$idx] == 0) {
            $table[$i][$j] = 1;
          }
          else {
            $table[$i][$j] = 0;
          }
        }
      }
      else if (strlen($operand[$j]) >= 3 && strlen($operand[$j]) <= 5 && count_operator($operand[$j], $op) == 1) {
        //majemuk positif dan negatif a(..)b
        $top = str_split($operand[$j]);
        $idl = 0; $idr = 0; $found = false;

        foreach ($operand as $key1 => $value1) {
          $tmp = str_split($value1);
          if (!$found) {
            for ($p = 0; $p < count($tmp); $p++) {
              for ($q = 0; $q < count($op); $q++) {
                if ($tmp[$p] == $op[$q]) {
                  $idx = $p;
                  $found = true;
                  break;
                }
              }
            }
          }
          else {
            break;
          }
        }

        $tdata = str_split($operand[$j]);
        $del = $tdata[$idx];
        $tdata = explode($del, $operand[$j]);
        foreach ($operand as $key => $value) {
          if ($value == $tdata[0]) {
            $idl = $key;
          }
          if ($value == $tdata[1]) {
            $idr = $key;
          }
        }
        switch ($del) {
          case '&':
            for ($i = 0; $i < $row; $i++) { //both side must true(1)
              if ($table[$i][$idl] == 1 && $table[$i][$idr] == 1) {
                $table[$i][$j] = 1;
              }
              else {
                $table[$i][$j] = 0;
              }
            }
          break;
          case '|':
            for ($i = 0; $i < $row; $i++) { //one or two side must true(1)
              if ($table[$i][$idl] == 1 || $table[$i][$idr] == 1) {
                $table[$i][$j] = 1;
              }
              else {
                $table[$i][$j] = 0;
              }
            }
          break;
          case '@':
            for ($i = 0; $i < $row; $i++) { //only true(1) if one side is true
              if (($table[$i][$idl] == 1 || $table[$i][$idr] == 0) || $table[$i][$idl] == 0 || $table[$i][$idr] == 1) {
                $table[$i][$j] = 1;
              }
              else {
                $table[$i][$j] = 0;
              }
            }
          break;
          case '>':
            for ($i = 0; $i < $row; $i++) { //set false(0) only if left side is true but right side is false
              if ($table[$i][$idl] == 1 && $table[$i][$idr] == 0) {
                $table[$i][$j] = 0;
              }
              else {
                $table[$i][$j] = 1;
              }
            }
          break;
          case '<':
            for ($i = 0; $i < $row; $i++) { //only true(1) if both side is true or both side is false
              if (($table[$i][$idl] == 1 || $table[$i][$idr] == 1) || $table[$i][$idl] == 0 || $table[$i][$idr] == 0) {
                $table[$i][$j] = 1;
              }
              else {
                $table[$i][$j] = 0;
              }
            }
          break;
          default:break;
        }
      }
      else {
        //bug
      }
    }
    return $table;
  }

  function result_truth_table($operand, $step, $form, $row, $var) {
    $table = array();
    $curr = 99;//masih belum tepat, hrusnya cari lgi, sementara pakai ini lalu re-index
    $prms = "";
    for ($i = 0; $i < count($form)-1; $i++) {
      $prms .= $form[$i];
      if ($i < count($form)-2) {
         $prms .= "&";
      }
      array_push($operand, $form[$i]);
    }
    array_push($operand, $prms);
    array_push($operand, $prms . ">" . $form[count($form)-1]); // p1, p2..pn -> q
    $operand = array_values(array_unique(destroy_empty_array($operand)));
    sort($operand);

    $operand = sort_charlen(sort_charlen($operand));

    for ($j = 0; $j < count($form)-1; $j++) { //compound proposition
      foreach ($operand as $key1 => $value1) {
        if ($value1 == $form[$j]) {

          for ($i = 0; $i < $row; $i++) {
            if ($j == 0) {
              $curr = count($form)+count($var);
              $step[$i][$curr] = $step[$i][$key1];
            }
            else {
              if ($step[$i][$curr] == 1 && $step[$i][$key1] == 1) {
                $step[$i][$curr] = 1;
              }
              else {
                $step[$i][$curr] = 0;
              }
            }

          }
        }
      }
    }

    foreach ($operand as $key1 => $value1) {
      if ($value1 == $form[count($form)-1]) {
        for ($i = 0; $i < $row; $i++) {
          if ($step[$i][$curr] == 1 && $step[$i][$key1] == 0) {
            $step[$i][$curr+1] = 0;
          }
          else {
            $step[$i][$curr+1] = 1;
          }
        }
      }
      else {
        //ambil dari var
      }
    }
    return $step;
  }

  function truth_value($con) {
    $res = "";
    if (array_sum($con) == count($con)) {
      $res = "<b>Tautologi</b>";
    }
    else if (array_sum($con) == 0) {
      $res = "<b>Kontradiksi</b>";
    }
    else {
      $res = "<i>fallacy</i>";
    }
    return $res;
  }

  function argument_conclusion($con, $col) {
    $data = '<b style="font-size:14pt;">sahih(<i>valid</i>)</b>/<strike>palsu(<i>invalid</i>)</strike>';
    foreach ($con as $key => $value) {
      if ($value[$col] == 0) {
        $data = '<strike>sahih(<i>valid</i>)</strike>/<b style="font-size:14pt;">palsu(<i>invalid</i>)</b>';
        break;
      }
    }
    return $data;
  }

  function show_notes() {
    echo '
      <div class="row">
        <div class="col-md-6"><b>Petunjuk & Catatan Penggunaan:</b></div>
        <div class="col-md-6"><b>Log:</b></div>
      </div>
      <div class="row" style="margin-top:5px;">
        <div class="col-md-6">
          <ul type="square">
            <li>Pisahkan proposisi dengan tanda baca titik.</li>
            <li>Proposisi 1..(n-1) diasumsikan sebagai premis.</li>
            <li>Proposisi n diasumsikan sebagai konklusi.</li>
            <li>Penulisan proposisi harus sesuai antar variabel satu dengan lainnya (makan &ne; mkn)</li>
            <li>Biarkan kolom kosong isian kosong apabila tidak digunakan.</li>
            <li>Tekan tombol hapus argumen untuk menghapus semua proposisi yang sudah ditulis.</li>
            <li>Tekan tombol periksa argumen untuk menganalisis argumen.</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul type="square">
            <li>Jauh dari kata sempurna, hanya berupaya sebaik mungkin.</li>
            <li><i>Feel free contact me <a href="https://www.gurisa.com/contact/">@gurisa.com</a></i></li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5"><b>Contoh Argumen:</b></div>
        <div class="col-md-5"><b>Contoh Proposisi:</b></div>
      </div>
      <div class="row" style="margin-top:5px;">
        <div class="col-md-5">
          <blockquote style="font-size:11pt;">
            Jika saya tidak makan maka saya lapar.<br />
            Saya tidak makan.<br />
            Saya lapar.
          </blockquote>
        </div>
        <div class="col-md-5">
          <ul type="square">
            <li>[Negasi (&not;)] Saya makan [Implikasi (&rarr;)] Saya lapar</li>
            <li>[Negasi (&not;)] Saya makan</li>
            <li>Saya lapar</li>
          </ul>
        </div>
        <div class="col-md-2">
          <button type="button" id="terapkan-1" onclick="terapkan(1);" class="btn btn-md btn-warning"><a style="text-decoration:none; color:#ffffff;" href="#">Terapkan <span class="glyphicon glyphicon-repeat"></span></a></button>
        </div>
      </div>
      ';
  }

  function show_entered_form($data) {
    $frm = '
    <form method="POST" action="' . $_SERVER["PHP_SELF"] . '" class="form-inline" style="text-align:center;">
      <div class="row">
        <div class="col-md-12">';
          for ($i = 1; $i <= 7; $i++) {
            $frm .= '
              <label>Proposisi ' . $i . '</label>
              <div class="form-group">
                <select name="operator-binary-' . $i . '-a" class="form-control">
                  <option value="positive"></option>
                  <option value="negative">Negasi (&not;)</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="proposisi-' . $i . '-a" placeholder="Proposisi atomik positif.." />
              </div>
              <div class="form-group">
                <select name="operator-majemuk-' . $i . '" class="form-control">
                  <option value="blank"></option>
                  <option value="conjunction">Konjungsi (&and;)</option>
                  <option value="disjunction">Disjungsi (&or;)</option>
                  <option value="excdisjunction">Disjungsi Eksklusif (&oplus;)</option>
                  <option value="conditional">Implikasi (&rarr;)</option>
                  <option value="biconditional">Bi-implikasi (&harr;)</option>
                </select>
              </div>
              <div class="form-group">
                <select name="operator-binary-' . $i . '-b" class="form-control">
                  <option value="positive"></option>
                  <option value="negative">Negasi (&not;)</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="proposisi-' . $i . '-b" placeholder="Proposisi atomik positif.." />
              </div>
              <br />
          ';
          }
    $frm .= '
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="form-group">
          <button type="reset" class="btn btn-md btn-danger">Hapus Argumen</button>
          &nbsp;
          <button type="submit" class="btn btn-md btn-primary">Periksa Argumen</button>
          &nbsp;
          <button type="button" data-toggle="modal" data-target="#info" class="btn btn-md btn-success">?</button>
          </div>
        </div>
      </div>
    </form>
    ';
    echo $frm;
  }
?>
