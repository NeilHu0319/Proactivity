<?php
$reference_array = task::get_reference();
?>

<div style="margin-top:40px;"></div>
<div id="div_activityreference" runat="server" style="background-color: #e3f2fd; padding: 30px; border-radius: 12px; max-width: 80%; margin:auto">
    <div style="margin-bottom: 30px; text-align: center;">
        <h4 style="font-weight: bold; color: #1976d2; text-transform: uppercase; letter-spacing: 1px;">References</h4>
    </div>
    <div id="div_reference" class="border rounded bg-white shadow p-4" style="border-color: #90caf9;">
        <ul class="list-group">
            <?php
            $display_id = 1;
            if (!empty($reference_array) && count($reference_array) > 0) {
                foreach ($reference_array as $one_reference) {
                    $type = ($one_reference['Item_Type'] == "Completed Task") ? "Finished Task" : $one_reference['Item_Type'];
                    if ($one_reference['Item_Type'] == "Completed Task") {
                        $verb = "Finished at ";
                    } elseif ($one_reference['Item_Type'] == "Award") {
                        $verb = "Received at ";
                    } elseif ($one_reference['Item_Type'] == "To-Do Task") {
                        $verb = "Due at ";
                    } else {
                        $verb = "";
                    }

                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #bbdefb; border-color: #64b5f6; border-radius: 8px; margin-bottom: 10px; padding: 15px;">
                        <span style="font-size: 16px; color: #0d47a1;"><b><?php echo $type; ?>:</b>&nbsp;&nbsp;<?php echo $one_reference['Item_Title']; ?></span>
                        <span style="color: #1565c0; font-size: 14px;"><?php echo $verb . $one_reference['Item_date']; ?></span>
                    </li>
                    <?php
                    $display_id++;
                }
            } else {
                ?>
                <li class="list-group-item text-center text-muted" style="background-color: #e3f2fd; border: none;"> No references available.
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>

