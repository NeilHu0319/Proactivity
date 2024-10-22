<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: index.php");
    exit();
}

include "class/uc_al.class.php";
uc_list::SynUCAppAward();
uc_list::SynUCAppActivity();
$uc_display_list = uc_list::GetDisplayList();
$tag_array = [];
foreach ($uc_display_list as $one_item) {
    $name = $one_item['Item_Title'];
    array_push($tag_array, $name);
}

$essay_json = file_get_contents('json/essay.json');
$essay_array = json_decode($essay_json, true);
$cur_obj = null;
foreach ($essay_array as $key => $one_item) {
    if ($one_item['user_id'] == $_SESSION['user'] && $one_item['school'] == 'uc') {
        $cur_obj = $essay_array[$key];
    }
}

if (isset($_POST['btn_save'])) {
    if ($cur_obj == null) {
        $new_item = [
            "user_id" => $_SESSION['user'],
            "school" => 'uc',
            "essay1" => trim($_POST['textarea_essay1']),
            "essay2" => trim($_POST['textarea_essay2']),
            "essay3" => trim($_POST['textarea_essay3']),
            "essay4" => trim($_POST['textarea_essay4']),
            "essay5" => trim($_POST['textarea_essay5']),
            "essay6" => trim($_POST['textarea_essay6']),
            "essay7" => trim($_POST['textarea_essay7']),
            "essay8" => trim($_POST['textarea_essay8'])
        ];
        array_push($essay_array, $new_item);
    } else {
        foreach ($essay_array as $key => $one_item) {
            if ($one_item['user_id'] == $_SESSION['user'] && $one_item['school'] == 'uc') {
                $essay_array[$key] = [
                    "user_id" => $_SESSION['user'],
                    "school" => 'uc',
                    "essay1" => trim($_POST['textarea_essay1']),
                    "essay2" => trim($_POST['textarea_essay2']),
                    "essay3" => trim($_POST['textarea_essay3']),
                    "essay4" => trim($_POST['textarea_essay4']),
                    "essay5" => trim($_POST['textarea_essay5']),
                    "essay6" => trim($_POST['textarea_essay6']),
                    "essay7" => trim($_POST['textarea_essay7']),
                    "essay8" => trim($_POST['textarea_essay8'])
                ];
            }
        }
    }

    file_put_contents('json/essay.json', json_encode($essay_array, JSON_PRETTY_PRINT));
    echo '<script>alert("Information has been saved")</script>';
    header("Refresh:0");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/badges/" />
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/components/dropdowns/" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <title>Essay Brainstorming - Proactivity</title>
    <script src="js/essay_brainstorming.js"></script>
    <style>
        .drop-area {
            height: 50px;
            border: 2px dashed #ccc;
            padding: 10px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            overflow-x: auto;
        }

        .draggable-area {
            display: flex;
            flex-wrap: wrap;
        }

        .draggable-block {
            width: 300px;
            height: 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            line-height: 20px;
            cursor: move;
            margin-top: 30px;
        }

    </style>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="height: 60px; width: 80%; margin: auto" class="row">
            <div class="col-9">
                <div style="margin-top:20px"><h4>UC Application Personal insight questions</h4></div>
                <div id="essay_1">
                    <div style="margin-top: 20px; font-weight:bold">Essay 1: Describe an example of your leadership experience in which you have positively influenced others, helped resolve disputes, or contributed to group efforts over time. (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 1</div>
                    <div class="drop-area" id="drop-area-essay-1"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay1" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 1"><?php echo $cur_obj['essay1']; ?></textarea>
                    </div>
                </div>
                <div id="essay_2">
                    <div style="margin-top: 20px; font-weight:bold">Essay 2 : Every person has a creative side, and it can be expressed in many ways: problem solving, original and innovative thinking, and artistically, to name a few. Describe how you express your creative side. (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 2</div>
                    <div class="drop-area" id="drop-area-essay-2"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay2" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 2"><?php echo $cur_obj['essay2']; ?></textarea>
                    </div>
                </div>
                <div id="essay_3">
                    <div style="margin-top: 20px; font-weight:bold">Essay 3 : What would you say is your greatest talent or skill? How have you developed and demonstrated that talent over time? (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 3</div>
                    <div class="drop-area" id="drop-area-essay-3"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay3" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 3"><?php echo $cur_obj['essay3']; ?></textarea>
                    </div>
                </div>
                <div id="essay_4">
                    <div style="margin-top: 20px; font-weight:bold">Essay 4 : Describe how you have taken advantage of a significant educational opportunity or worked to overcome an educational barrier you have faced. (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 4</div>
                    <div class="drop-area" id="drop-area-essay-4"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay4" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 4"><?php echo $cur_obj['essay4']; ?></textarea>
                    </div>
                </div>
                <div id="essay_5">
                    <div style="margin-top: 20px; font-weight:bold">Essay 5 : Describe the most significant challenge you have faced and the steps you have taken to overcome this challenge. How has this challenge affected your academic achievement? (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 5</div>
                    <div class="drop-area" id="drop-area-essay-5"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay5" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 5"><?php echo $cur_obj['essay5']; ?></textarea>
                    </div>
                </div>
                <div id="essay_6">
                    <div style="margin-top: 20px; font-weight:bold">Essay 6 : Think about an academic subject that inspires you. Describe how you have furthered this interest inside and/or outside of the classroom. (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 6</div>
                    <div class="drop-area" id="drop-area-essay-6"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay6" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 6"><?php echo $cur_obj['essay6']; ?></textarea>
                    </div>
                </div>
                <div id="essay_7">
                    <div style="margin-top: 20px; font-weight:bold">Essay 7 : What have you done to make your school or your community a better place? (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 7</div>
                    <div class="drop-area" id="drop-area-essay-7"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay7" rows="3" maxlength="350" placeholder="Please enter additional brainstorming idea for essay 7"><?php echo $cur_obj['essay7']; ?></textarea>
                    </div>
                </div>
                <div id="essay_8">
                    <div style="margin-top: 20px; font-weight:bold">Essay 8 : Beyond what has already been shared in your application, what do you believe makes you a strong candidate for admissions to the University of California?. (250-350 words)</div>
                    <div style="font-size: small; margin-top: 20px">Please drag related activity or award tag to this area for essay 8</div>
                    <div class="drop-area" id="drop-area-essay-8"></div>
                    <div style="margin-top: 20px">
                        <textarea class="form-control" name="textarea_essay8" rows="3" maxlength="350" placeholder="Please enter additional brainstorming ideas for essay 8"><?php echo $cur_obj['essay8']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row">
                    <div class="col-6">
                        <button type="submit" name="btn_save" style="margin-top:20px" class="btn btn-primary btn-sm">Save Ideas</button>
                    </div>
                    <div class="col-6">
                        <button id="Btn_Reset" style="margin-top:20px" class="btn btn-primary btn-sm">Reset Tag</button>
                    </div>
                </div>
                <div style="margin-top:50px" id="tag_container">
                    <?php
                    $id = 1;
                    foreach ($tag_array as $one_tag) {
                        echo ' <div id="block' . $id . '" class="draggable-block">' . $one_tag . '</div>';
                        $id++;
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
</body>
</html>