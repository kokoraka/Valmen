<!DOCTYPE html>
<!--
  Nah, tired doing all this stuff by myself.
  Feel free to contact me @gurisa.com (hope there're some stuffs for me)
  Btw, this app also available on my server: http://go.gurisa.com/matdisk/
  ~Raka
-->
<html>
<head>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" />
  <style>
    .row {
      margin-top:3px;
      margin-bottom:3px;
    }
    .form-group {
      margin-top:3px;
      margin-bottom:3px;
    }
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelompok 1 (RIP) | Validitas Argumen | Matematika Diskrit - 7</title>
</head>
<body>
  <?php include_once("functions.php"); ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 style="text-align:center;"><a href="index.php" style="text-decoration:none; color:#000000;">Kelompok 1 (RIP)</a></h1>
        <p style="text-align:center;">Validitas Argumen</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <p style="text-align:center;">Aplikasi ini dibuat untuk memenuhi tugas mata kuliah <b>Matematika Diskrit - 7</b>.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <hr />
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="form-inline" style="text-align:center;">
          <div class="row">
            <div class="col-md-12">
              <?php for ($i = 1; $i <= 7; $i++) { ?>
              <label>Proposisi <?php echo $i; ?></label>
              <div class="form-group">
                <select id="operator-binary-<?php echo $i; ?>-a" name="operator-binary-<?php echo $i; ?>-a" class="form-control">
                  <option value="positive"></option>
                  <option value="negative">Negasi (&not;)</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" id="proposisi-<?php echo $i; ?>-a" class="form-control" name="proposisi-<?php echo $i; ?>-a" placeholder="Proposisi atomik positif.." />
              </div>
              <div class="form-group">
                <select id="operator-majemuk-<?php echo $i; ?>" name="operator-majemuk-<?php echo $i; ?>" class="form-control">
                  <option value="blank"></option>
                  <option value="conjunction">Konjungsi (&and;)</option>
                  <option value="disjunction">Disjungsi (&or;)</option>
                  <option value="excdisjunction">Disjungsi Eksklusif (&oplus;)</option>
                  <option value="conditional">Implikasi (&rarr;)</option>
                  <option value="biconditional">Bi-implikasi (&harr;)</option>
                </select>
              </div>
              <div class="form-group">
                <select id="operator-binary-<?php echo $i; ?>-b" name="operator-binary-<?php echo $i; ?>-b" class="form-control">
                  <option value="positive"></option>
                  <option value="negative">Negasi (&not;)</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" id="proposisi-<?php echo $i; ?>-b" class="form-control" name="proposisi-<?php echo $i; ?>-b" placeholder="Proposisi atomik positif.." />
              </div>
              <br />
              <?php } ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5 col-md-offset-2" style="text-align:center; float:none; margin:0 auto;">
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
      </div>
    </div>

    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <hr />
        <?php if (compare($_SERVER["REQUEST_METHOD"], "POST", "L")) { ?>
          <?php if (proposition_entered($_POST) && proposition_count($_POST) >= 2) { ?>
            <?php
              $max = proposition_count($_POST);
              $var = get_variable($_POST, $max);
              $form = get_formulation($_POST, $max, $vnm);
            ?>
            <div class="row">
              <div class="col-md-12"><h4 style="text-align:center;">Hasil Pengolahan</h4></div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <b>Argumen:</b>
                <br />
                <?php show_argument($_POST, $max); ?>
              </div>
              <div class="col-md-6">
                <b>Proposisi:</b>
                <br />
                <?php show_proposition($_POST, $max); ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <b>Variabel:</b>
                <br />
                <?php show_variable($var, $vnm); ?>
              </div>
              <div class="col-md-6">
                <b>Formulasi:</b>
                <br />
                <?php show_formulation($form, $op); ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><h4 style="text-align:center;">Tabel Kebenaran</h4>
                <?php
                  $row = pow(2, count($var));
                  $operand = get_all_operand($_POST, $form, $max, $vnm, count($var));
                  $btable = base_truth_table(count($var));
                  $stable = step_truth_table($operand, $btable, $form, $var, $row, $op);
                  $rtable = result_truth_table($operand, $stable, $form, $row, $var);
                  foreach ($rtable as $key => $value) {
                    $rtable[$key] = array_values($value);
                  }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                      <?php for ($j = 0; $j < count($operand); $j++) { ?>
                      <th style="text-align:center;"><?php echo rewrite_proposition($operand[$j], $op); ?></th>
                      <?php } ?>
                    </tr>
                    <?php for ($i = 0; $i < $row; $i++) { ?>
                    <tr>
                      <?php for ($j = 0; $j < count($operand); $j++) { ?>
                        <?php if ($j < count($var)) { ?>
                          <td style="text-align:center;"><?php echo rewrite_value($btable[$i][$j]); ?></td>
                        <?php } else if ($j >= count($var) && $j < count($operand)-2) { ?>
                          <td style="text-align:center;"><?php echo rewrite_value($stable[$i][$j]); ?></td>
                        <?php } else { ?>
                          <?php if ($j == count($operand)-2) { ?>
                            <td style="text-align:center;"><?php echo rewrite_value($rtable[$i][$j]); ?></td>
                          <?php } else { ?>
                            <td style="text-align:center;" class="<?php if ($rtable[$i][$j] == 0) { echo "bg-danger"; } else { echo "bg-info"; }?>"><?php echo rewrite_value($rtable[$i][$j]); ?></td>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                    </tr>
                    <?php } ?>
                    <tr>
                      <td colspan="<?php echo $j-1; ?>"></td>
                      <td style="text-align:center;"><?php echo truth_value(array_column($rtable, $j-1)); ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><h4 style="text-align:center;">Kesimpulan</h4></div>
            </div>
            <div class="row">
              <div class="col-md-12">
                Setelah dilakukan pengolahan dan analisis informasi, aplikasi mendeteksi bahwasanya argumen: <br />
                <blockquote style="font-size:11pt; margin-top:2px; margin-bottom:2px; font-style:italic;">
                  <?php show_argument($_POST, $max); ?>
                </blockquote>
                merupakan argumen yang <?php echo argument_conclusion($rtable, $j-1); ?>.
              </div>
            </div>
          <?php } else { ?>
            <p class="bg-danger" style="padding:15px;">Oops, tidak ditemukan proposisi untuk dianalisis. <br />Silahkan masukkan proposisi di kolom '<i>proposisi atomik positif</i>'.</p>
            <?php show_notes(); ?>
          <?php }?>
        <?php } else { ?>
          <?php show_notes(); ?>
        <?php } ?>
        <hr />
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <p style="text-align:center;"><b style="font-size:14pt;">R</b>aka (10115253), <b style="font-size:14pt;"><strike>I</b>khsan (10115220)</strike>, <strike><b style="font-size:14pt;">P</b>aulus (10112781)</strike></p>
        <p style="text-align:center;">Copyright &copy; 2017 Kelompok 1 (RIP) All Rights Reserved</p>
        <p style="text-align:center;">Universitas Komputer Indonesia</p>
      </div>
    </div>
  </div>

  <div id="info" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">RIP</h4>
      </div>
      <div class="modal-body">
        <blockquote style="font-size:11pt;">
          I am thankful for all of those who said <strong>no</strong> to me.<br />
          Its because of them i'm doing it myself.<br />
          <br />
          ~Albert Einstein
        </blockquote>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="assets/bootstrap/js/jquery.js"></script>
  <script type="text/javascript" src="assets/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" lang="javascript">
    /* Fungsi tombol terapkan 1 (contoh) */
    var try1 = document.getElementById("terapkan-1");
    var proposisi1a = document.getElementById("proposisi-1-a");
    var proposisi1b = document.getElementById("proposisi-1-b");
    var proposisi2a = document.getElementById("proposisi-2-a");
    var proposisi3a = document.getElementById("proposisi-3-a");
    var opbin1a = document.getElementById("operator-binary-1-a");
    var opbin2a = document.getElementById("operator-binary-2-a");
    var opmaj1 = document.getElementById("operator-majemuk-1");

    function terapkan(a) {
      if (a == 1) {
        opbin1a.selectedIndex = 1;
        opbin2a.selectedIndex = 1;
        opmaj1.selectedIndex = 4;
        proposisi1a.value = "Saya makan";
        proposisi1b.value = "Saya lapar";
        proposisi2a.value = "Saya makan";
        proposisi3a.value = "Saya lapar";
      }
    }
  </script>
</body>
</html>

<!--
  References:
  http://getbootstrap.com/css/
  http://php.net/manual/en/function.array-shift.php
  http://php.net/manual/en/function.array-unique.php
  http://php.net/manual/en/function.pow.php
  http://php.net/manual/en/function.str-split.php
  https://ethicalrealism.wordpress.com/2013/01/19/logic-part-4-how-to-make-truth-tables/
  https://stackoverflow.com/questions/10757671/how-to-remove-line-breaks-no-characters-from-the-string
  https://stackoverflow.com/questions/18153234/center-a-column-using-twitter-bootstrap-3
  https://stackoverflow.com/questions/369602/delete-an-element-from-an-array
  https://stackoverflow.com/questions/3911261/php-new-line-in-textarea
  https://stackoverflow.com/questions/7558022/php-reindex-array
  https://stackoverflow.com/questions/9291987/outputting-a-truth-table-in-php
  https://www.w3schools.com/bootstrap/bootstrap_modal.asp
  https://www.w3schools.com/bootstrap/bootstrap_ref_comp_glyphs.asp
  https://www.w3schools.com/php/func_array_column.asp
  https://www.w3schools.com/php/func_array_push.asp
  https://www.w3schools.com/php/func_array_shift.asp
  https://www.w3schools.com/php/func_array_sort.asp
  https://www.w3schools.com/php/func_array_sum.asp
  https://www.w3schools.com/php/func_array_unique.asp
  https://www.w3schools.com/php/func_string_rtrim.asp
  https://www.w3schools.com/php/php_arrays_sort.asp
-->
