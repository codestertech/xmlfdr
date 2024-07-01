<?php
/**
 * @package xmlfdr
 */
/*
Plugin Name: xmlfdr
Description: Used by millions, Import the horse auction data from xml Feed API and proceed it.
Version: 4.0.1
Author: codestertech
Author URI: http://codestertech.com
License: GPLv2 or later
Text Domain: xmlfdr
*/

<?php
/*
Plugin Name: XML Feeds Reader
Description: A plugin to read and store XML feeds data
*/

defined('ABSPATH') || die('You cannot access this file directly');

// Register session
function xmlfdr_register_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'xmlfdr_register_session');

// Activation hook
function xmlfdr_activate() {
    global $wpdb;

    // Create table
    $table_name = $wpdb->prefix . 'xmlfeeds_events';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `Sport` VARCHAR(255) NOT NULL,
        `sportType` INT(11) NOT NULL DEFAULT '0',
        `EventID` VARCHAR(255) NOT NULL,
        `Meeting` VARCHAR(255) NOT NULL,
        `RaceNum` VARCHAR(255) NOT NULL,
        `Description` VARCHAR(255) NOT NULL,
        `OutcomeAt` VARCHAR(255) NOT NULL,
        `SuspendAt` VARCHAR(255) NOT NULL,
        `Num` VARCHAR(255) NOT NULL,
        `League` VARCHAR(255) NOT NULL,
        `TeamA` VARCHAR(100) NOT NULL,
        `TeamB` VARCHAR(100) NOT NULL,
        `AWin` VARCHAR(100) NOT NULL,
        `BWin` VARCHAR(100) NOT NULL,
        `ALine` VARCHAR(100) NOT NULL,
        `ALineDiv` VARCHAR(100) NOT NULL,
        `BLine` VARCHAR(100) NOT NULL,
        `BLineDiv` VARCHAR(100) NOT NULL,
        `Draw` VARCHAR(100) NOT NULL,
        `TotalOver` VARCHAR(100) NOT NULL,
        `OverDiv` VARCHAR(100) NOT NULL,
        `TotalUnder` VARCHAR(100) NOT NULL,
        `UnderDiv` VARCHAR(100) NOT NULL,
        `AMgn1Div` VARCHAR(100) NOT NULL,
        `AMgn2Div` VARCHAR(100) NOT NULL,
        `BMgn1Div` VARCHAR(100) NOT NULL,
        `BMgn2Div` VARCHAR(100) NOT NULL,
        `Team` VARCHAR(100) NOT NULL,
        `Win` VARCHAR(100) NOT NULL,
        `Place` VARCHAR(100) NOT NULL,
        `Eliminated` VARCHAR(100) NOT NULL,
        `BookMakerLink` VARCHAR(255) NOT NULL,
        `status` INT(11) NOT NULL DEFAULT '0',
        `state` LONGTEXT NOT NULL,
        `clicks` INT(11) NOT NULL DEFAULT '0',
        `short_code` VARCHAR(255) NOT NULL,
        `color_code` VARCHAR(255) NOT NULL,
        `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );";
    $wpdb->query($sql);

    // Insert data
    $xml = simplexml_load_file('https://feeds.topsport.com.au/championbets.asp');
    foreach ($xml->children() as $child) {
        $attri = $child->attributes();

        // Australian Rules
        if (!empty($attri["Sport"]) && $attri["Sport"] == "Australian Rules") {
            foreach ($child->m as $val) {
                $row = $val->attributes();
                $wpdb->insert(
                    $table_name,
                    array(
                        'Sport' => (string) $attri->Sport,
                        'sportType' => 1,
                        'OutcomeAt' => (string) $attri->OutcomeAt,
                        'SuspendAt' => (string) $attri->SuspendAt,
                        'EventID' => (string) $attri->EventID,
                        'Description' => (string) $attri->Description,
                        'League' => (string) $attri->League,
                        'TeamA' => (string) $row->TeamA,
                        'TeamB' => (string) $row->TeamB,
                        'AWin' => (string) $row->AWin,
                        'BWin' => (string) $row->BWin,
                        'ALine' => (string) $row->ALine,
                        'ALineDiv' => (string) $row->ALineDiv,
                        'BLine' => (string) $row->BLine,
                        'BLineDiv' => (string) $row->BLineDiv,
                        'Draw' => (string) $row->Draw,
                        'TotalOver' => (string) $row->TotalOver,
                        'OverDiv' => (string) $row->OverDiv,
                        'TotalUnder' => (string) $row->TotalUnder,
                        'UnderDiv' => (string) $row->UnderDiv,
                        'AMgn1Div' => (string) $row->AMgn1Div,
                        'AMgn2Div' => (string) $row->AMgn2Div,
                        'BMgn1Div' => (string) $row->BMgn1Div,
                        'BMgn2Div' => (string) $row->BMgn2Div,
                    )
                );
            }
        }

        // Horse Racing
        if (!empty($attri["Sport"]) && $attri["Sport"] == "Horse Racing") {
            foreach ($child->c as $val) {
                $row = $val->attributes();
                $wpdb->insert(
                    $table_name,
                    array(
                        'Sport' => (string) $attri->Sport,
                        'portType' => 2,
                        'EventID' => (string) $attri->EventID,
                        'Meeting' => (string) $attri->Meeting,
                        'RaceNum' => (string) $attri->RaceNum,
                        'Description' => (string) $attri->Description,
                        'OutcomeAt' => (string) $attri->OutcomeAt,
                        'SuspendAt' => (string) $attri->SuspendAt,
                        'Num' => (string) $attri->Num,
                        'Team' => (string) $row->Team,
                        'Win' => (string) $row->Win,
                        'Place' => (string) $row->Place,
                        'Eliminated' => (string) $row->Eliminated,
                    )
                );
            }
        }

        // Greyhound Racing
        if (!empty($attri["Sport"]) && $attri["Sport"] == "Greyhound Racing") {
            foreach ($child->c as $val) {
                $row = $val->attributes();
                $wpdb->insert(
                    $table_name,
                    array(
                        'Sport' => (string) $attri->Sport,
                        'portType' => 3,
                        'EventID' => (string) $attri->EventID,
                        'Meeting' => (string) $attri->Meeting,
                        'RaceNum' => (string) $attri->RaceNum,
                        'Description' => (string) $attri->Description,
                        'OutcomeAt' => (string) $attri->OutcomeAt,
                        'SuspendAt' => (string) $attri->SuspendAt,
                        'Num' => (string) $attri->Num,
                        'Team' => (string) $row->Team,
                        'Win' => (string) $row->Win,
                        'Place' => (string) $row->Place,
                        'Eliminated' => (string) $row->Eliminated,
                    )
                );
            }
        }

        // Harness Racing
        if (!empty($attri["Sport"]) && $attri["Sport"] == "Harness Racing") {
            foreach ($child->c as $val) {
                $row = $val->attributes();
                $wpdb->insert(
                    $table_name,
                    array(
                        'Sport' => (string) $attri->Sport,
                        'portType' => 4,
                        'EventID' => (string) $attri->EventID,
                        'Meeting' => (string) $attri->Meeting,
                        'RaceNum' => (string) $attri->RaceNum,
                        'Description' => (string) $attri->Description,
                        'OutcomeAt' => (string) $attri->OutcomeAt,
                        'SuspendAt' => (string) $attri->SuspendAt,
                        'Num' => (string) $attri->Num,
                        'Team' => (string) $row->Team,
                        'Win' => (string) $row->Win,
                        'Place' => (string) $row->Place,
                        'Eliminated' => (string) $row->Eliminated,
                    )
                );
            }
        }
    }
}

function xmlfdr_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix. 'xmlfeeds_events';
    $wpdb->query("DROP TABLE IF EXISTS $table_name;");
}

register_activation_hook(__FILE__, 'xmlfdr_activate');
register_deactivation_hook(__FILE__, 'xmlfdr_deactivate');

// Load Custom Resources
function custom_enqueue_script() {
    wp_enqueue_script('jquery-js', plugin_dir_url(__FILE__). 'assets/data_table/jquery-3.5.1.js');
}

add_action('admin_enqueue_scripts', 'custom_enqueue_script');

/**
 * Plugin Menus
 */
function xmlFeeder_menu()
{
    add_menu_page('XML Feeder', 'XML Feeder', 'manage_options', 'xml-feeder', 'xml_feeder_func');
    add_submenu_page('xml-feeder', 'Australian Rules', 'Australian Rules', 'manage_options', 'xml-feeder-australian-rules', 'xml_feeder_manage_australian_rule_func');
    add_submenu_page('xml-feeder', 'Horse Racing', 'Horse Racing', 'manage_options', 'xml-feeder-horse-racing', 'xml_feeder_manage_horse_racing_func1');
    add_submenu_page('xml-feeder', 'Greyhound Racing', 'Greyhound Racing', 'manage_options', 'xml-feeder-greyhound_racing', 'xml_feeder_manage_greyhound_racing_func2');
    add_submenu_page('xml-feeder', 'Harness Racing', 'Harness Racing', 'manage_options', 'xml-feeder-harness-racing', 'xml_feeder_manage_harness_racing_func3');
    add_submenu_page('xml-feeder', 'Update Feeds', 'Update Feeds', 'manage_options', 'xml-feeder-update', 'xml_feeder_manage_update_feeds');
}

function xml_feeder_manage_update_feeds()
{
    ?>
    <style>
        #example_filter {
            margin-right: 2% !important;
        }

        .container_class {
            margin-top: 2%;
        }
    </style>
    <div class="container_class">

        <?php if (isset($_SESSION['msg_1'])) { ?>
            <style>
                .alert {
                    padding: 20px;
                    background-color: #04AA6D;
                    color: white;
                    width: 96%;
                    margin-bottom: 2%;
                }

                .closebtn {
                    margin-left: 15px;
                    color: white;
                    font-weight: bold;
                    float: right;
                    font-size: 22px;
                    line-height: 20px;
                    cursor: pointer;
                    transition: 0.3s;
                }

                .closebtn:hover {
                    color: black;
                }
            </style>

            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                unset($_SESSION['msg_1']); ?>
            </div>
        <?php } ?>
        <style>
            .frm_upd {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .sb {
                width: 90%;
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .sb:hover {
                background-color: #45a049;
            }

        </style>
        <h2> Update XML Feeds </h2>
        <hr>
        <?php if (isset($_GET['act']) && $_GET['act'] == 'update_feeds') {
            ?>
            <h3>Please Wait, It may take Few Minutes.</h3>
        <?php

        global $wpdb;
        // clear old data
        $table_name = $wpdb->prefix . "xmlfeeds_events";
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);

        // Load new updated data from Live XML feed to Database
        //sql for Table
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
              `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
              `Sport` varchar(255) NOT NULL,
              `sportType` INT( 11 ) NOT NULL DEFAULT '0',
              `EventID` varchar(255) NOT NULL,
              `Meeting` varchar(255) NOT NULL,
              `RaceNum` varchar(255) NOT NULL,
              `Description` varchar(255) NOT NULL,
              `OutcomeAt` varchar(255) NOT NULL,
              `SuspendAt` varchar(255) NOT NULL,
              `Num` varchar(255) NOT NULL,
              `League` varchar(255) NOT NULL,
              `TeamA` varchar(100) NOT NULL,
              `TeamB` varchar(100) NOT NULL,
              `AWin` varchar(100) NOT NULL,
              `BWin` varchar(100) NOT NULL,
              `ALine` varchar(100) NOT NULL,
              `ALineDiv` varchar(100) NOT NULL,
              `BLine` varchar(100) NOT NULL,
              `BLineDiv` varchar(100) NOT NULL,
              `Draw` varchar(100) NOT NULL,
              `TotalOver` varchar(100) NOT NULL,
              `OverDiv` varchar(100) NOT NULL,
              `TotalUnder` varchar(100) NOT NULL,
              `UnderDiv` varchar(100) NOT NULL,
              `AMgn1Div` varchar(100) NOT NULL,
              `AMgn2Div` varchar(100) NOT NULL,
              `BMgn1Div` varchar(100) NOT NULL,
              `BMgn2Div` varchar(100) NOT NULL,
              `Team` varchar(100) NOT NULL,
              `Win` varchar(100) NOT NULL,
              `Place` varchar(100) NOT NULL,
              `Eliminated` varchar(100) NOT NULL,
              `BookMakerLink` varchar(255) NOT NULL,
              `status` INT( 11 ) NOT NULL DEFAULT '0',
              `state` LONGTEXT NOT NULL,
              `clicks` INT( 11 ) NOT NULL DEFAULT '0',
              `short_code` VARCHAR(255) NOT NULL,
              `color_code` VARCHAR(255) NOT NULL ,
              `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id)
            ) ;";
        $wpdb->query($sql);

        // add data to table
        $xml = simplexml_load_file("https://feeds.topsport.com.au/championbets.asp");
        foreach ($xml->children() as $child) {
            $attri = $child->attributes();

            //Australian Rules
            if (!empty($attri["Sport"]) && $attri["Sport"] == "Australian Rules") {

                foreach ($child->m as $val) {
                    $row = $val->attributes();

                    $sql_1 = "INSERT INTO $table_name (Sport, sportType, OutcomeAt, SuspendAt,EventID,Description,League,
                TeamA, TeamB, AWin, BWin, ALine, ALineDiv, BLine, BLineDiv,Draw,TotalOver,OverDiv,TotalUnder,UnderDiv,
                AMgn1Div,AMgn2Div,BMgn1Div,BMgn2Div) VALUES (
                '" . (string)$attri->Sport . "',
                '1',
                '" . (string)$attri->OutcomeAt . "',
                '" . (string)$attri->SuspendAt . "',
                '" . (string)$attri->EventID . "',
                '" . (string)$attri->Description . "',
                '" . (string)$attri->League . "',
                '" . (string)$row->TeamA . "',
                '" . (string)$row->TeamB . "',
                '" . (string)$row->AWin . "',
                '" . (string)$row->BWin . "',
                '" . (string)$row->ALine . "',
                '" . (string)$row->ALineDiv . "',
                '" . (string)$row->BLine . "',
                '" . (string)$row->BLineDiv . "',
                '" . (string)$row->Draw . "',
                '" . (string)$row->TotalOver . "',
                '" . (string)$row->OverDiv . "',
                '" . (string)$row->TotalUnder . "',
                '" . (string)$row->UnderDiv . "',
                '" . (string)$row->AMgn1Div . "',
                '" . (string)$row->AMgn2Div . "',
                '" . (string)$row->BMgn1Div . "',
                '" . (string)$row->BMgn2Div . "'
                )";
                    $wpdb->query($sql_1);

                }
            }

            //Horse Racing
            if (!empty($attri["Sport"]) && $attri["Sport"] == "Horse Racing") {

                foreach ($child->c as $val) {
                    $row = $val->attributes();
                    // insert into DB

                    $sql_2 = "INSERT INTO $table_name (Sport, sportType,EventID,Meeting,RaceNum,Description,OutcomeAt,SuspendAt,
                Num,Team,Win,Place,Eliminated) VALUES (
                '" . (string)$attri->Sport . "',
                '2',
                '" . (string)$attri->EventID . "',
                '" . (string)$attri->Meeting . "',
                '" . (string)$attri->RaceNum . "',
                '" . (string)$attri->Description . "',
                '" . (string)$attri->OutcomeAt . "',
                '" . (string)$attri->SuspendAt . "',
                '" . (string)$attri->Num . "',
                '" . (string)$row->Team . "',
                '" . (string)$row->Win . "',
                '" . (string)$row->Place . "',
                '" . (string)$row->Eliminated . "'
                )";
                    $wpdb->query($sql_2);
                }
            }
//        //Greyhound Racing
            if (!empty($attri["Sport"]) && $attri["Sport"] == "Greyhound Racing") {

                foreach ($child->c as $val) {
                    $row = $val->attributes();
                    // insert into DB
                    $sql_3 = "INSERT INTO $table_name (Sport, sportType, EventID,Meeting,RaceNum,Description,OutcomeAt,SuspendAt,
                Num,Team,Win,Place,Eliminated) VALUES (
                '" . (string)$attri->Sport . "',
                '3',
                '" . (string)$attri->EventID . "',
                '" . (string)$attri->Meeting . "',
                '" . (string)$attri->RaceNum . "',
                '" . (string)$attri->Description . "',
                '" . (string)$attri->OutcomeAt . "',
                '" . (string)$attri->SuspendAt . "',
                '" . (string)$attri->Num . "',
                '" . (string)$row->Team . "',
                '" . (string)$row->Win . "',
                '" . (string)$row->Place . "',
                '" . (string)$row->Eliminated . "'
                )";
                    $wpdb->query($sql_3);
                }
            }
            //Harness Racing
            if (!empty($attri["Sport"]) && $attri["Sport"] == "Harness Racing") {

                foreach ($child->c as $val) {
                    $row = $val->attributes();
                    $sql_4 = "INSERT INTO $table_name (Sport, sportType, EventID,Meeting,RaceNum,Description,OutcomeAt,SuspendAt,
                Num,Team,Win,Place,Eliminated) VALUES (
                '" . (string)$attri->Sport . "',
                '4',
                '" . (string)$attri->EventID . "',
                '" . (string)$attri->Meeting . "',
                '" . (string)$attri->RaceNum . "',
                '" . (string)$attri->Description . "',
                '" . (string)$attri->OutcomeAt . "',
                '" . (string)$attri->SuspendAt . "',
                '" . (string)$attri->Num . "',
                '" . (string)$row->Team . "',
                '" . (string)$row->Win . "',
                '" . (string)$row->Place . "',
                '" . (string)$row->Eliminated . "'
                )";
                    $wpdb->query($sql_4);

                }
            }
        }

        $_SESSION['msg_1'] = "XML feeds updated successfully";
        echo "<h1>XML feeds updated successfully</h1>";

        echo "<script>window.location.href='admin.php?page=xml-feeder-update';</script>";
        exit;

        } else { ?>
            <a href="?page=xml-feeder-update&act=update_feeds">
                <input type="Button" class="sb" value="Update XML Feeds"></a>
            <script>
                function work_in_progress() {
                    alert("Work in Progress!");
                    return false;
                }
            </script>
        <?php } ?>
    </div>
    <hr/>
    </div>
    <?php
}

function xml_feeder_manage_australian_rule_func()
{

    global $wpdb;
    $table_name = $wpdb->prefix . "xmlfeeds_events";

    $search_term = '';
    if (isset($_GET['search_term'])) {
        $search_term = $_GET['search_term'];
    }


    ?>
    <script>
        $('.wp-first-item').hide();
    </script>
    <style>
        #example_filter {
            margin-right: 2% !important;
        }

        .container_class {
            margin-top: 2%;
        }
    </style>
<img src="<?php echo plugin_dir_url(__FILE__) ?>assets/img/ajax-loader.gif" id="loader" style="display: none;">
    <div class="container_class">

        <?php if (isset($_SESSION['msg_1'])) { ?>
            <style>
                .alert {
                    padding: 20px;
                    background-color: #04AA6D;
                    color: white;
                    width: 96%;
                    margin-bottom: 2%;
                }

                .closebtn {
                    margin-left: 15px;
                    color: white;
                    font-weight: bold;
                    float: right;
                    font-size: 22px;
                    line-height: 20px;
                    cursor: pointer;
                    transition: 0.3s;
                }

                .closebtn:hover {
                    color: black;
                }
            </style>

            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                unset($_SESSION['msg_1']); ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['val']) && $_GET['act'] == 'upd') {
            $id = $_GET['val'];
            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");

            ?>
            <style>
                .frm_upd {
                    width: 100%;
                    padding: 12px 20px;
                    margin: 8px 0;
                    display: inline-block;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                .sb {
                    width: 100%;
                    background-color: #4CAF50;
                    color: white;
                    padding: 14px 20px;
                    margin: 8px 0;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .sb:hover {
                    background-color: #45a049;
                }

            </style>
            <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                <h3>Update <?php echo $res[0]->sport; ?></h3>
                <hr/>
                <form action="<?php echo admin_url('admin.php'); ?>" method="post">
                    <label for="sport"><b>Sport</b></label>
                    <input type="text" value="<?php echo $res[0]->Sport; ?>" class="frm_upd" id="sport" name="sport"
                           placeholder="Sport..">

                    <label for=""><b>Event ID</b></label>
                    <input type="text" value="<?php echo $res[0]->EventID; ?>" class="frm_upd" name="event_id"/>

                    <label for=""><b>Team</b></label>
                    <input type="text" value="<?php echo $res[0]->Team; ?>" class="frm_upd" name="team"/>

                    <label for=""><b>Win</b></label>
                    <input type="text" value="<?php echo $res[0]->Win; ?>" class="frm_upd" name="win"/>

                    <label for=""><b>Place</b></label>
                    <input type="text" value="<?php echo $res[0]->Place; ?>" class="frm_upd" name="place"/>

                    <label for=""><b>Eliminated</b></label>
                    <input type="text" value="<?php echo $res[0]->Eliminated; ?>" class="frm_upd" name="eliminated"/>

                    <label for=""><b>BookMakerLink</b></label>
                    <input type="text" value="<?php echo $res[0]->BookMakerLink; ?>" class="frm_upd"
                           name="BookMakerLink"/>


                    <input type="hidden" name="id" value="<?php echo $res[0]->id; ?>"/>
                    <input type="hidden" name="action" value="wpse10500"/>
                    <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">

                    <input type="submit" class="sb" value="Submit">
                </form>
            </div>
            <hr/>
            <?php
        } ?>

        <?php if (isset($_GET['val']) && $_GET['act'] == 'summary') {
            $id = $_GET['val'];
            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
            ?>
            <style>
                .frm_upd {
                    width: 100%;
                    padding: 12px 20px;
                    margin: 8px 0;
                    display: inline-block;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                .sb {
                    width: 100%;
                    background-color: #4CAF50;
                    color: white;
                    padding: 14px 20px;
                    margin: 8px 0;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .sb:hover {
                    background-color: #45a049;
                }

            </style>
            <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">
                <h3>Clicks Summary <?php echo $res[0]->sport; ?></h3>
                <hr/>
                <table>
                    <tr>
                        <td><strong>Clicks</strong></td>
                        <td><?php echo $res[0]->clicks; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Info</strong></td>
                        <td><?php echo $res[0]->state; ?></td>
                    </tr>
                </table>
                <a href="admin.php?page=xml-feeder-australian-rules"> <input type="Button" class="sb" value="Back"></a>
            </div>
            <hr/>
            <?php
        } ?>

        <?php if (isset($_GET['val']) && $_GET['act'] == 'short_code') {
            $id = $_GET['val'];
            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
            $short_code = $res[0]->short_code;
            $short_code = explode(',', $short_code);
            ?>
            <style>
                .frm_upd {
                    width: 100%;
                    padding: 12px 20px;
                    margin: 8px 0;
                    display: inline-block;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                .sb {
                    width: 100%;
                    background-color: #4CAF50;
                    color: white;
                    padding: 14px 20px;
                    margin: 8px 0;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .sb:hover {
                    background-color: #45a049;
                }

            </style>
            <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                <h3>Select Column to Update Short Code For <?php echo $res[0]->sport; ?></h3>
                <hr/>
                <table>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (in_array('UnderDiv', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="UnderDiv">
                            <label for="vehicle3"> UnderDiv</label><br>
                            <input type="checkbox" <?php if (in_array('Description', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="Description">
                            <label for="vehicle2"> Description</label><br>
                            <input type="checkbox" <?php if (in_array('OutcomeAt', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="OutcomeAt">
                            <label for="vehicle3"> OutcomeAt</label><br>
                            <input type="checkbox" <?php if (in_array('SuspendAt', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="SuspendAt">
                            <label for="vehicle3"> SuspendAt</label><br>
                            <input type="checkbox" <?php if (in_array('League', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="League">
                            <label for="vehicle3"> League</label><br>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (in_array('TeamA', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="TeamA">
                            <label for="vehicle1"> TeamA</label><br>
                            <input type="checkbox" <?php if (in_array('TeamB', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="TeamB">
                            <label for="vehicle2"> TeamB</label><br>
                            <input type="checkbox" <?php if (in_array('AWin', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="AWin">
                            <label for="vehicle3"> AWin</label><br>
                            <input type="checkbox" <?php if (in_array('BWin', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="BWin">
                            <label for="vehicle3"> BWin</label><br>
                            <input type="checkbox" <?php if (in_array('ALine', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="ALine">
                            <label for="vehicle3"> ALine</label><br>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (in_array('ALineDiv', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="ALineDiv">
                            <label for="vehicle1"> ALineDiv</label><br>
                            <input type="checkbox" <?php if (in_array('BLine', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="BLine">
                            <label for="vehicle2"> BLine</label><br>
                            <input type="checkbox" <?php if (in_array('BLineDiv', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="BLineDiv">
                            <label for="vehicle3"> BLineDiv</label><br>
                            <input type="checkbox" <?php if (in_array('Draw', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="Draw">
                            <label for="vehicle3"> Draw</label><br>
                            <input type="checkbox" <?php if (in_array('BMgn2Div', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="BMgn2Div">
                            <label for="vehicle3"> BMgn2Div</label><br>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (in_array('TotalOver', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="TotalOver">
                            <label for="vehicle1"> TotalOver</label><br>
                            <input type="checkbox" <?php if (in_array('OverDiv', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="OverDiv">
                            <label for="vehicle2"> OverDiv</label><br>
                            <input type="checkbox" <?php if (in_array('TotalUnder', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="TotalUnder">
                            <label for="vehicle3"> TotalUnder</label><br>
                            <input type="checkbox" <?php if (in_array('BMgn1Div', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="BMgn1Div">
                            <label for="vehicle2"> BMgn1Div</label><br>
                            <input type="checkbox" <?php if (in_array('AMgn1Div', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="AMgn1Div">
                            <label for="vehicle3"> AMgn1Div</label><br>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (in_array('AMgn2Div', $short_code)) {
                                echo "checked";
                            } ?> name="Sport" value="AMgn2Div">
                            <label for="vehicle1"> AMgn2Div</label><br>


                        </td>
                    </tr>
                    <tr>
                        <td colspan="5"><input type="button" name="short_code_gen" class="sb"
                                               onclick="return generate_short_code();" value="Update Short Code"/></td>
                    </tr>
                </table>


                <br>
            </div>
            <hr/>
            <script>
                function generate_short_code() {
                    var col = [];
                    col.length = 0;

                    $('input[name="Sport"]:checked').each(function () {
                        console.log(this.value);
                        col.push(this.value);
                    });

                    var upd_col = col.join();
                    console.log('upd_col ', upd_col);

                    window.location.href = "?page=<?php echo $_GET['page']; ?>&val=<?php echo $id; ?>&act=short_code_upd&cols=" + upd_col;
                }
            </script>
            <?php
        } ?>

        <?php if (isset($_GET['val']) && $_GET['act'] == 'short_code_upd') {
            global $wpdb;
            $table_name = $wpdb->prefix . "xmlfeeds_events";
            $cols = $_GET['cols'];
            $id = $_GET['val'];

            $sql = "UPDATE  $table_name SET short_code = '" . $cols . "' WHERE id= $id";
            $wpdb->query($sql);

            $_SESSION['msg_1'] = 'Record Updated successfully ';
            echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
            exit;
        } ?>

        <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code_upd') {
            global $wpdb;
            $table_name = $wpdb->prefix . "xmlfeeds_events";
            $cols = $_GET['cols'];
            $id = $_GET['val'];

            $sql = "UPDATE  $table_name SET color_code = '" . $cols . "' WHERE id= $id";
            $wpdb->query($sql);

            $_SESSION['msg_1'] = 'Record Updated successfully ';
            echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
            exit;
        } ?>


        <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code') {
            $id = $_GET['val'];
            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
            $color_code = $res[0]->color_code;

            if (isset($color_code) && !empty($color_code)) {
                $color_code = explode(',', $color_code);
                $text_color = $color_code[0];
                $back_ground_color = $color_code[1];
            } else {
                $text_color = "000000";
                $back_ground_color = "ffb80c";
            }

            $text_color = '#' . $text_color;
            $back_ground_color = '#' . $back_ground_color;


            ?>
            <style>
                .frm_upd {
                    width: 100%;
                    padding: 12px 20px;
                    margin: 8px 0;
                    display: inline-block;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                .sb {
                    width: 100%;
                    background-color: #4CAF50;
                    color: white;
                    padding: 14px 20px;
                    margin: 8px 0;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .sb:hover {
                    background-color: #45a049;
                }

            </style>
            <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                <h3>Update Color Code <?php echo $res[0]->sport; ?></h3>
                <hr/>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <strong>Text Color</strong>
                        </td>
                        <td>
                            <input type="color" id="text_color" name="text_color"
                                   value="<?php echo $text_color; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Background Color</strong>
                        </td>
                        <td>
                            <input type="color" id="back_ground_color" name="back_ground_color"
                                   value="<?php echo $back_ground_color; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="sb" onclick="return generate_color_code();">Update Color</button>
                        </td>
                    </tr>
                </table>


                <br>
            </div>
            <hr/>
            <script>
                function generate_color_code() {
                    col = [];
                    var text_color = $("#text_color").val();
                    text_color = text_color.replace('#', '');
                    col.push(text_color);
                    var back_ground_color = $("#back_ground_color").val();
                    back_ground_color = back_ground_color.replace('#', '');
                    col.push(back_ground_color);

                    var upd_col = col.join();
                    console.log('upd_col ', upd_col);

                    window.location.href = encodeURI("?page=<?php echo $_GET['page']; ?>&val=<?php echo $id; ?>&act=color_code_upd&cols=" + upd_col);
                }
            </script>
            <?php
        } ?>

        <?php

        if (isset($_GET['val']) && $_GET['act'] == 'del') {
            global $wpdb;
            $table_name = $wpdb->prefix . "xmlfeeds_events";
            $id = $_GET['val'];

            $sql = "DELETE FROM $table_name WHERE id= $id";
            $wpdb->query($sql);

            $_SESSION['msg_1'] = 'Record deleted successfully ';
            echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
            exit;
        }

        if (isset($_GET['val']) && $_GET['act'] == 'status_update') {
            global $wpdb;
            $table_name = $wpdb->prefix . "xmlfeeds_events";
            $id = $_GET['val'];
            $status = $_GET['status'];

            $sql = "UPDATE $table_name SET status = $status WHERE id= $id";
            $wpdb->query($sql);

            $_SESSION['msg_1'] = 'Status updated successfully ';
            echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
            exit;
        }

        ?>

        <?php
        require_once('pagination.class.php');

        $count_sql = '';

        if (!empty($search_term)) {

            $search_term = explode('::', $search_term);
            $column = $search_term[0];
            $search_type = $search_term[1];

            $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 1 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC";
        } else {
            $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 1 ORDER BY id ASC";
        }

        $items = count($wpdb->get_results($count_sql));


        if ($items > 0) {
            $p = new pagination;
            $p->items($items);
            $p->limit(30); // Limit entries per page
            $p->target("admin.php?page=" . $_GET['page']);
            $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
            $p->calculate(); // Calculates what to show
            $p->parameterName('paging');
            $p->adjacents(1); //No. of page away from the current page

            if (!isset($_GET['paging'])) {
                $p->page = 1;
            } else {
                $p->page = $_GET['paging'];
            }

            //Query for limit paging
            $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

        } else {
            echo "No Record Found";
        }

        ?>
        <style>
            .pagination {
                display: inline-block;
            }

            .pagination a {
                color: black;
                float: left;
                padding: 8px 16px;
                text-decoration: none;
                transition: background-color .3s;
                border: 1px solid #ddd;
            }

            .active {
                background-color: #4CAF50;
                color: white;
                border: 1px solid #4CAF50;
            }

            .pagination a:hover:not(.active) {
                background-color: #ddd;
            }

            #customers {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #customers td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #customers tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #customers tr:hover {
                background-color: #ddd;
            }

            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #4CAF50;
                color: white;
            }
        </style>

        <div class="wrap">
            <h2> <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                    echo "Recently Updated";
                } ?> Australian Rules XML Feeds <a
                        href="?page=<?php echo $_GET['page']; ?>&act=recently_updated_records"
                        style="margin-left: 2%; font-size: small;">Recently Updated</a></h2>


            <div class="tablenav">

                <?php
                $search_term = '';
                if (isset($_GET['search_term'])) {
                    $search_term = $_GET['search_term'];

                    $search_term = explode('::', $search_term);
                    $column = $search_term[0];
                    $search_type = $search_term[1];
                }

                ?>

                <input type="text" id="search2" style="margin:auto;max-width:300px" placeholder="Search Sports..."
                       name="search2" value="<?php if (isset($search_type)) {
                    echo $search_type;
                } ?>">
                <select name="search_type" id="search_type" style="height: 28px;margin-bottom: 3px;">
                    <option <?php if (isset($column) && $column == 'EventID') { ?> selected <?php } ?> value="EventID">
                        EventID
                    </option>
                    <option <?php if (isset($column) && $column == 'Description') { ?> selected <?php } ?>
                            value="Description">Description
                    </option>
                    <option <?php if (isset($column) && $column == 'OutcomeAt') { ?> selected <?php } ?>
                            value="OutcomeAt">OutcomeAt
                    </option>
                    <option <?php if (isset($column) && $column == 'SuspendAt') { ?> selected <?php } ?>
                            value="SuspendAt">SuspendAt
                    </option>
                    <option <?php if (isset($column) && $column == 'League') { ?> selected <?php } ?> value="League">
                        League
                    </option>
                    <option <?php if (isset($column) && $column == 'TeamA') { ?> selected <?php } ?> value="TeamA">
                        TeamA
                    </option>
                    <option <?php if (isset($column) && $column == 'TeamB') { ?> selected <?php } ?> value="TeamB">
                        TeamB
                    </option>
                    <option <?php if (isset($column) && $column == 'AWin') { ?> selected <?php } ?> value="AWin">AWin
                    </option>
                    <option <?php if (isset($column) && $column == 'BWin') { ?> selected <?php } ?> value="BWin">BWin
                    </option>
                    <option <?php if (isset($column) && $column == 'ALine') { ?> selected <?php } ?> value="ALine">
                        ALine
                    </option>
                    <option <?php if (isset($column) && $column == 'ALineDiv') { ?> selected <?php } ?>value="ALineDiv">
                        ALineDiv
                    </option>
                    <option <?php if (isset($column) && $column == 'BLine') { ?> selected <?php } ?> value="BLine">
                        BLine
                    </option>
                    <option <?php if (isset($column) && $column == 'BLineDiv') { ?> selected <?php } ?>value="BLineDiv">
                        BLineDiv
                    </option>
                    <option <?php if (isset($column) && $column == 'Draw') { ?> selected <?php } ?> value="Draw">Draw
                    </option>
                    <option <?php if (isset($column) && $column == 'TotalOver') { ?> selected <?php } ?>
                            value="TotalOver">TotalOver
                    </option>
                    <option <?php if (isset($column) && $column == 'OverDiv') { ?> selected <?php } ?> value="OverDiv">
                        OverDiv
                    </option>
                    <option <?php if (isset($column) && $column == 'UnderDiv') { ?> selected <?php } ?>value="UnderDiv">
                        UnderDiv
                    </option>
                    <option <?php if (isset($column) && $column == 'AMgn1Div') { ?> selected <?php } ?>value="AMgn1Div">
                        AMgn1Div
                    </option>
                    <option <?php if (isset($column) && $column == 'AMgn2Div') { ?> selected <?php } ?>value="AMgn2Div">
                        AMgn2Div
                    </option>
                    <option <?php if (isset($column) && $column == 'BMgn1Div') { ?> selected <?php } ?>value="BMgn1Div">
                        BMgn1Div
                    </option>
                    <option <?php if (isset($column) && $column == 'BMgn2Div') { ?> selected <?php } ?>value="BMgn2Div">
                        BMgn2Div
                    </option>
                </select>
                <button type="button" onclick="return do_search();" style="height: 28px;">Search</button>

                <script>
                    var page = "<?php echo $_GET['page']; ?>";

                    function do_search() {
                        var val = $('#search2').val();
                        var search_type = $('#search_type option:selected').text();
                        if (!val) {
                            alert("Search Term Required!");
                            return false;
                        }

                        val = search_type + '::' + val;
                        window.location.href = '?page=' + page + '&search_term=' + val;
                    }
                </script>
                <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                } else { ?>
                    <div class='tablenav-pages'>
                        <?php echo $p->show();  // Echo out the list of paging.
                        ?>
                    </div>
                <?php } ?>
            </div>
            <style>
                .Container1 {
                    width: 100%;
                    /*overflow-y: auto;*/
                    overflow: auto;
                    height: 550px;
                }

                .Content1 {
                    width: 100%;
                }

                .Flipped, .Flipped .Content1 {
                    transform: rotateX(359deg);
                    -ms-transform: rotateX(359deg);
                    -webkit-transform: rotateX(359deg);
                }

                th {
                    position: -webkit-sticky;
                    position: sticky;
                    top: 0;
                    z-index: 2;
                }

                th[scope=row] {
                    position: -webkit-sticky;
                    position: sticky;
                    left: 0;
                    z-index: 1;
                }
            </style>
            <div class="Container1 Flipped">
                <div class="Content1">
                    <table id="customers" class="widefat">
                        <thead>
                        <tr>
                            <th>Sport</th>
                            <th>EventID</th>
                            <th>Description</th>
                            <th>OutcomeAt</th>
                            <th>SuspendAt</th>
                            <th>League</th>
                            <th>TeamA</th>
                            <th>TeamB</th>
                            <th>AWin</th>
                            <th>BWin</th>
                            <th>ALine</th>
                            <th>ALineDiv</th>
                            <th>BLine</th>
                            <th>BLineDiv</th>
                            <th>Draw</th>
                            <th>TotalOver</th>
                            <th>OverDiv</th>
                            <th>TotalUnder</th>
                            <th>UnderDiv</th>
                            <!--                            <th>AMgn1Div</th>-->
                            <!--                            <th>AMgn2Div</th>-->
                            <!--                            <th>BMgn1Div</th>-->
                            <!--                            <th>BMgn2Div</th>-->
                            <th>Update Colors</th>
                            <th>Clicks Summary</th>
                            <th>Short Code</th>
                            <th>Status</th>
                            <th>Update Status</th>
                            <th>Action's</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $search_term = '';
                        if (isset($_GET['search_term'])) {
                            $search_term = $_GET['search_term'];
                        }

                        if (!empty($search_term)) {

                            $search_term = explode('::', $search_term);
                            $column = $search_term[0];
                            $search_type = $search_term[1];

                            $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 1 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC $limit";
                        } else {
                            $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 1 ORDER BY id ASC $limit";
                        }

                        if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                            $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 1 ORDER BY updated_at DESC LIMIT 50";
                        }

                        $result = $wpdb->get_results($res_sql);

                        ?>
                        <?php foreach ((array)$result as $val) { ?>
                            <tr>
                                <td><?php echo $val->Sport; ?></td>
                                <td><?php echo $val->EventID; ?></td>
                                <td><?php echo $val->Description; ?></td>
                                <td><?php echo $val->OutcomeAt; ?></td>
                                <td><?php echo $val->SuspendAt; ?></td>
                                <td><?php echo $val->League; ?></td>

                                <td><?php echo $val->TeamA; ?></td>
                                <td><?php echo $val->TeamB; ?></td>
                                <td><?php echo $val->AWin; ?></td>
                                <td><?php echo $val->BWin; ?></td>
                                <td><?php echo $val->ALine; ?></td>
                                <td><?php echo $val->ALineDiv; ?></td>
                                <td><?php echo $val->BLine; ?></td>
                                <td><?php echo $val->BLineDiv; ?></td>
                                <td><?php echo $val->Draw; ?></td>
                                <td><?php echo $val->TotalOver; ?></td>
                                <td><?php echo $val->OverDiv; ?></td>
                                <td><?php echo $val->TotalUnder; ?></td>
                                <td><?php echo $val->UnderDiv; ?></td>
                                <!--                                <td>--><?php //echo $val->AMgn1Div; ?><!--</td>-->
                                <!--                                <td>--><?php //echo $val->AMgn2Div; ?><!--</td>-->
                                <!--                                <td>--><?php //echo $val->BMgn1Div; ?><!--</td>-->
                                <!--                                <td>--><?php //echo $val->BMgn2Div; ?><!--</td>-->
                                <td>
                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=color_code">Update
                                        Colors</a></td>
                                <td>
                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=summary">Summary</a>
                                </td>

                                <td> <?php if (!empty($val->short_code)) {
                                        ?>
                                        [xmlfdr id="<?php echo $val->id; ?>" col="<?php echo $val->short_code; ?>"]
                                        <?php
                                    } else { ?>
                                        [xmlfdr id="<?php echo $val->id; ?>"]
                                    <?php } ?>
                                    |
                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=short_code">update
                                        Code</a>
                                </td>
                                <td>
                                    <?php if ($val->status == 0) {
                                        echo "Active";
                                    } else {
                                        echo "In Active";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($val->status == 0) {
                                        ?>
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=1&act=status_update"
                                           class="confirmation">Deactivate</a>
                                        <?php
                                    } ?>
                                    <?php if ($val->status == 1) {
                                        ?>
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=0&act=status_update"
                                           class="confirmation">Activate</a>
                                        <?php
                                    } ?>
                                </td>
                                <td>
                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=upd">Update</a>
                                    |
                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=del"
                                       class="confirmation">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            var elems = document.getElementsByClassName('confirmation');
            var confirmIt = function (e) {
                if (!confirm('Are you sure?')) e.preventDefault();
            };
            for (var i = 0, l = elems.length; i < l; i++) {
                elems[i].addEventListener('click', confirmIt, false);
            }
        </script>
        <?php
        }
        function xml_feeder_manage_horse_racing_func1()
        {

        global $wpdb;
        $table_name = $wpdb->prefix . "xmlfeeds_events";

        $search_term = '';
        if (isset($_GET['search_term'])) {
            $search_term = $_GET['search_term'];
        }


        ?>
        <script>
            $('.wp-first-item').hide();
        </script>
        <style>
            #example_filter {
                margin-right: 2% !important;
            }

            .container_class {
                margin-top: 2%;
            }
        </style>
        <img src="<?php echo plugin_dir_url(__FILE__) ?>assets/img/ajax-loader.gif" id="loader" style="display: none;">
        <div class="container_class">

            <?php if (isset($_SESSION['msg_1'])) { ?>
                <style>
                    .alert {
                        padding: 20px;
                        background-color: #04AA6D;
                        color: white;
                        width: 96%;
                        margin-bottom: 2%;
                    }

                    .closebtn {
                        margin-left: 15px;
                        color: white;
                        font-weight: bold;
                        float: right;
                        font-size: 22px;
                        line-height: 20px;
                        cursor: pointer;
                        transition: 0.3s;
                    }

                    .closebtn:hover {
                        color: black;
                    }
                </style>

                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                    unset($_SESSION['msg_1']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['val']) && $_GET['act'] == 'upd') {
                $id = $_GET['val'];
                $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");

                ?>
                <style>
                    .frm_upd {
                        width: 100%;
                        padding: 12px 20px;
                        margin: 8px 0;
                        display: inline-block;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    .sb {
                        width: 100%;
                        background-color: #4CAF50;
                        color: white;
                        padding: 14px 20px;
                        margin: 8px 0;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .sb:hover {
                        background-color: #45a049;
                    }

                </style>
                <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                    <h3>Update <?php echo $res[0]->sport; ?></h3>
                    <hr/>
                    <form action="<?php echo admin_url('admin.php'); ?>" method="post">
                        <label for="sport"><b>Sport</b></label>
                        <input type="text" value="<?php echo $res[0]->Sport; ?>" class="frm_upd" id="sport" name="sport"
                               placeholder="Sport..">

                        <label for=""><b>Event ID</b></label>
                        <input type="text" value="<?php echo $res[0]->EventID; ?>" class="frm_upd" name="event_id"/>

                        <label for=""><b>Team</b></label>
                        <input type="text" value="<?php echo $res[0]->Team; ?>" class="frm_upd" name="team"/>

                        <label for=""><b>Win</b></label>
                        <input type="text" value="<?php echo $res[0]->Win; ?>" class="frm_upd" name="win"/>

                        <label for=""><b>Place</b></label>
                        <input type="text" value="<?php echo $res[0]->Place; ?>" class="frm_upd" name="place"/>

                        <label for=""><b>Eliminated</b></label>
                        <input type="text" value="<?php echo $res[0]->Eliminated; ?>" class="frm_upd"
                               name="eliminated"/>

                        <label for=""><b>BookMakerLink</b></label>
                        <input type="text" value="<?php echo $res[0]->BookMakerLink; ?>" class="frm_upd"
                               name="BookMakerLink"/>


                        <input type="hidden" name="id" value="<?php echo $res[0]->id; ?>"/>
                        <input type="hidden" name="action" value="wpse10500"/>
                        <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">

                        <input type="submit" class="sb" value="Submit">
                    </form>
                </div>
                <hr/>
                <?php
            } ?>

            <?php if (isset($_GET['val']) && $_GET['act'] == 'summary') {
                $id = $_GET['val'];
                $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                ?>
                <style>
                    .frm_upd {
                        width: 100%;
                        padding: 12px 20px;
                        margin: 8px 0;
                        display: inline-block;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    .sb {
                        width: 100%;
                        background-color: #4CAF50;
                        color: white;
                        padding: 14px 20px;
                        margin: 8px 0;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .sb:hover {
                        background-color: #45a049;
                    }

                </style>
                <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">
                    <h3>Clicks Summary <?php echo $res[0]->sport; ?></h3>
                    <hr/>
                    <table>
                        <tr>
                            <td><strong>Clicks</strong></td>
                            <td><?php echo $res[0]->clicks; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Info</strong></td>
                            <td><?php echo $res[0]->state; ?></td>
                        </tr>
                    </table>
                    <a href="admin.php?page=xml-feeder-australian-rules"> <input type="Button" class="sb" value="Back"></a>
                </div>
                <hr/>
                <?php
            } ?>

            <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code_upd') {
                global $wpdb;
                $table_name = $wpdb->prefix . "xmlfeeds_events";
                $cols = $_GET['cols'];
                $id = $_GET['val'];

                $sql = "UPDATE  $table_name SET color_code = '" . $cols . "' WHERE id= $id";
                $wpdb->query($sql);

                $_SESSION['msg_1'] = 'Record Updated successfully ';
                echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                exit;
            } ?>


            <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code') {
                $id = $_GET['val'];
                $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                $color_code = $res[0]->color_code;

                if (isset($color_code) && !empty($color_code)) {
                    $color_code = explode(',', $color_code);
                    $text_color = $color_code[0];
                    $back_ground_color = $color_code[1];
                } else {
                    $text_color = "000000";
                    $back_ground_color = "ffb80c";
                }

                $text_color = '#' . $text_color;
                $back_ground_color = '#' . $back_ground_color;


                ?>
                <style>
                    .frm_upd {
                        width: 100%;
                        padding: 12px 20px;
                        margin: 8px 0;
                        display: inline-block;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        box-sizing: border-box;
                    }

                    .sb {
                        width: 100%;
                        background-color: #4CAF50;
                        color: white;
                        padding: 14px 20px;
                        margin: 8px 0;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .sb:hover {
                        background-color: #45a049;
                    }

                </style>
                <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                    <h3> Update Color Code <?php echo $res[0]->sport; ?></h3>
                    <hr/>
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <strong>Text Color</strong>
                            </td>
                            <td>
                                <input type="color" id="text_color" name="text_color"
                                       value="<?php echo $text_color; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Background Color</strong>
                            </td>
                            <td>
                                <input type="color" id="back_ground_color" name="back_ground_color"
                                       value="<?php echo $back_ground_color; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="sb" onclick="return generate_color_code();">Update Color</button>
                            </td>
                        </tr>
                    </table>


                    <br>
                </div>
                <hr/>
                <script>
                    function generate_color_code() {
                        col = [];
                        var text_color = $("#text_color").val();
                        text_color = text_color.replace('#', '');
                        col.push(text_color);
                        var back_ground_color = $("#back_ground_color").val();
                        back_ground_color = back_ground_color.replace('#', '');
                        col.push(back_ground_color);

                        var upd_col = col.join();
                        console.log('upd_col ', upd_col);

                        window.location.href = encodeURI("?page=<?php echo $_GET['page']; ?>&val=<?php echo $id; ?>&act=color_code_upd&cols=" + upd_col);
                    }
                </script>
                <?php
            } ?>

            <?php

            if (isset($_GET['val']) && $_GET['act'] == 'del') {
                global $wpdb;
                $table_name = $wpdb->prefix . "xmlfeeds_events";
                $id = $_GET['val'];

                $sql = "DELETE FROM $table_name WHERE id= $id";
                $wpdb->query($sql);

                $_SESSION['msg_1'] = 'Record deleted successfully ';
                echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                exit;
            }

            if (isset($_GET['val']) && $_GET['act'] == 'status_update') {
                global $wpdb;
                $table_name = $wpdb->prefix . "xmlfeeds_events";
                $id = $_GET['val'];
                $status = $_GET['status'];

                $sql = "UPDATE $table_name SET status = $status WHERE id= $id";
                $wpdb->query($sql);

                $_SESSION['msg_1'] = 'Status updated successfully ';
                echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                exit;
            }

            ?>

            <?php
            require_once('pagination.class.php');

            $count_sql = '';

            if (!empty($search_term)) {

                $search_term = explode('::', $search_term);
                $column = $search_term[0];
                $search_type = $search_term[1];

                $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 2 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC";
            } else {
                $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 2 ORDER BY id ASC";
            }

            $items = count($wpdb->get_results($count_sql));


            if ($items > 0) {
                $p = new pagination;
                $p->items($items);
                $p->limit(30); // Limit entries per page
                $p->target("admin.php?page=" . $_GET['page']);
                $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
                $p->calculate(); // Calculates what to show
                $p->parameterName('paging');
                $p->adjacents(1); //No. of page away from the current page

                if (!isset($_GET['paging'])) {
                    $p->page = 1;
                } else {
                    $p->page = $_GET['paging'];
                }

                //Query for limit paging
                $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

            } else {
                echo "No Record Found";
            }

            ?>
            <style>
                .pagination {
                    display: inline-block;
                }

                .pagination a {
                    color: black;
                    float: left;
                    padding: 8px 16px;
                    text-decoration: none;
                    transition: background-color .3s;
                    border: 1px solid #ddd;
                }

                .active {
                    background-color: #4CAF50;
                    color: white;
                    border: 1px solid #4CAF50;
                }

                .pagination a:hover:not(.active) {
                    background-color: #ddd;
                }

                #customers {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }

                #customers td, #customers th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }

                #customers tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                #customers tr:hover {
                    background-color: #ddd;
                }

                #customers th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #4CAF50;
                    color: white;
                }
            </style>

            <div class="wrap">
                <h2> <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                        echo "Recently Updated";
                    } ?> Horse Racing XML Feeds <a
                            href="?page=<?php echo $_GET['page']; ?>&act=recently_updated_records"
                            style="margin-left: 2%; font-size: small;">Recently Updated</a></h2>


                <div class="tablenav">

                    <?php
                    $search_term = '';
                    if (isset($_GET['search_term'])) {
                        $search_term = $_GET['search_term'];

                        $search_term = explode('::', $search_term);
                        $column = $search_term[0];
                        $search_type = $search_term[1];
                    }

                    ?>

                    <input type="text" id="search2" style="margin:auto;max-width:300px" placeholder="Search Sports..."
                           name="search2" value="<?php if (isset($search_type)) {
                        echo $search_type;
                    } ?>">
                    <select name="search_type" id="search_type" style="height: 28px;margin-bottom: 3px;">
                        <option <?php if (isset($column) && $column == 'EventID') { ?> selected <?php } ?>
                                value="EventID">EventID
                        </option>
                        <option <?php if (isset($column) && $column == 'Description') { ?> selected <?php } ?>
                                value="Description">Description
                        </option>
                        <option <?php if (isset($column) && $column == 'Meeting') { ?> selected <?php } ?>
                                value="OutcomeAt">Meeting
                        </option>
                        <option <?php if (isset($column) && $column == 'RaceNum') { ?> selected <?php } ?>
                                value="RaceNum">RaceNum
                        </option>
                        <option <?php if (isset($column) && $column == 'OutcomeAt') { ?> selected <?php } ?>
                                value="OutcomeAt">OutcomeAt
                        </option>
                        <option <?php if (isset($column) && $column == 'SuspendAt') { ?> selected <?php } ?>
                                value="SuspendAt">SuspendAt
                        </option>
                        <option <?php if (isset($column) && $column == 'Num') { ?> selected <?php } ?> value="Num">Num
                        </option>
                        <option <?php if (isset($column) && $column == 'Team') { ?> selected <?php } ?> value="Team">
                            Team
                        </option>
                        <option <?php if (isset($column) && $column == 'Win') { ?> selected <?php } ?> value="Win">Win
                        </option>
                        <option <?php if (isset($column) && $column == 'Place') { ?> selected <?php } ?> value="Place">
                            ALine
                        </option>
                        <option <?php if (isset($column) && $column == 'Eliminated') { ?> selected <?php } ?>
                                value="Eliminated">Eliminated
                        </option>
                    </select>
                    <button type="button" onclick="return do_search();" style="height: 28px;">Search</button>

                    <script>
                        var page = "<?php echo $_GET['page']; ?>";

                        function do_search() {
                            var val = $('#search2').val();
                            var search_type = $('#search_type option:selected').text();
                            if (!val) {
                                alert("Search Term Required!");
                                return false;
                            }

                            val = search_type + '::' + val;
                            window.location.href = '?page=' + page + '&search_term=' + val;
                        }
                    </script>
                    <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                    } else { ?>
                        <div class='tablenav-pages'>
                            <?php echo $p->show();  // Echo out the list of paging.
                            ?>
                        </div>
                    <?php } ?>
                </div>

                <style>
                    .Container1 {
                        width: 100%;
                        /*overflow-y: auto;*/
                        overflow: auto;
                        height: 550px;
                    }

                    .Content1 {
                        width: 100%;
                    }

                    .Flipped, .Flipped .Content1 {
                        transform: rotateX(359deg);
                        -ms-transform: rotateX(359deg);
                        -webkit-transform: rotateX(359deg);
                    }

                    th {
                        position: -webkit-sticky;
                        position: sticky;
                        top: 0;
                        z-index: 2;
                    }

                    th[scope=row] {
                        position: -webkit-sticky;
                        position: sticky;
                        left: 0;
                        z-index: 1;
                    }
                </style>

                <div class="Container1 Flipped">
                    <div class="Content1">

                        <table id="customers" class="widefat">
                            <thead>
                            <tr>
                                <th>Sport</th>
                                <th>EventID</th>
                                <th>Description</th>
                                <th>Meeting</th>
                                <th>RaceNum</th>
                                <th>OutcomeAt</th>
                                <th>SuspendAt</th>
                                <th>Num</th>
                                <th>Team</th>
                                <th>Win</th>
                                <th>Place</th>
                                <th>Eliminated</th>
                                <th>Color Code</th>
                                <th>Clicks Summary</th>
                                <th>Short Code</th>
                                <th>Status</th>
                                <th>Update Status</th>
                                <th>Action's</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $search_term = '';
                            if (isset($_GET['search_term'])) {
                                $search_term = $_GET['search_term'];
                            }

                            if (!empty($search_term)) {

                                $search_term = explode('::', $search_term);
                                $column = $search_term[0];
                                $search_type = $search_term[1];

                                $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 2 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC $limit";
                            } else {
                                $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 2 ORDER BY id ASC $limit";
                            }

                            if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                                $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 2 ORDER BY updated_at DESC LIMIT 50";
                            }

                            $result = $wpdb->get_results($res_sql);

                            ?>
                            <?php foreach ((array)$result as $val) { ?>
                                <tr>
                                    <td><?php echo $val->Sport; ?></td>
                                    <td><?php echo $val->EventID; ?></td>
                                    <td><?php echo $val->Description; ?></td>
                                    <td><?php echo $val->Meeting; ?></td>
                                    <td><?php echo $val->RaceNum; ?></td>
                                    <td><?php echo $val->OutcomeAt; ?></td>
                                    <td><?php echo $val->SuspendAt; ?></td>
                                    <td><?php echo $val->Num; ?></td>
                                    <td><?php echo $val->Team; ?></td>
                                    <td><?php echo $val->Win; ?></td>
                                    <td><?php echo $val->Place; ?></td>
                                    <td><?php echo $val->Eliminated; ?></td>
                                    <td>
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=color_code">Update
                                            Colors</a></td>
                                    <td>
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=summary">Summary</a>
                                    </td>
                                    <td>[xmlfdr id="<?php echo $val->id; ?>"]</td>
                                    <td>
                                        <?php if ($val->status == 0) {
                                            echo "Active";
                                        } else {
                                            echo "In Active";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($val->status == 0) {
                                            ?>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=1&act=status_update"
                                               class="confirmation">Deactivate</a>
                                            <?php
                                        } ?>
                                        <?php if ($val->status == 1) {
                                            ?>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=0&act=status_update"
                                               class="confirmation">Activate</a>
                                            <?php
                                        } ?>
                                    </td>
                                    <td>
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=upd">Update</a>
                                        |
                                        <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=del"
                                           class="confirmation">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <script type="text/javascript">
                var elems = document.getElementsByClassName('confirmation');
                var confirmIt = function (e) {
                    if (!confirm('Are you sure?')) e.preventDefault();
                };
                for (var i = 0, l = elems.length; i < l; i++) {
                    elems[i].addEventListener('click', confirmIt, false);
                }
            </script>
            <?php
            }
            function xml_feeder_manage_greyhound_racing_func2()
            {

            global $wpdb;
            $table_name = $wpdb->prefix . "xmlfeeds_events";

            $search_term = '';
            if (isset($_GET['search_term'])) {
                $search_term = $_GET['search_term'];
            }


            ?>
            <script>
                $('.wp-first-item').hide();
            </script>
            <style>
                #example_filter {
                    margin-right: 2% !important;
                }

                .container_class {
                    margin-top: 2%;
                }
            </style>
            <img src="<?php echo plugin_dir_url(__FILE__) ?>assets/img/ajax-loader.gif" id="loader"
                 style="display: none;">
            <div class="container_class">

                <?php if (isset($_SESSION['msg_1'])) { ?>
                    <style>
                        .alert {
                            padding: 20px;
                            background-color: #04AA6D;
                            color: white;
                            width: 96%;
                            margin-bottom: 2%;
                        }

                        .closebtn {
                            margin-left: 15px;
                            color: white;
                            font-weight: bold;
                            float: right;
                            font-size: 22px;
                            line-height: 20px;
                            cursor: pointer;
                            transition: 0.3s;
                        }

                        .closebtn:hover {
                            color: black;
                        }
                    </style>

                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                        unset($_SESSION['msg_1']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code_upd') {
                    global $wpdb;
                    $table_name = $wpdb->prefix . "xmlfeeds_events";
                    $cols = $_GET['cols'];
                    $id = $_GET['val'];

                    $sql = "UPDATE  $table_name SET color_code = '" . $cols . "' WHERE id= $id";
                    $wpdb->query($sql);

                    $_SESSION['msg_1'] = 'Record Updated successfully ';
                    echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                    exit;
                } ?>


                <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code') {
                    $id = $_GET['val'];
                    $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                    $color_code = $res[0]->color_code;

                    if (isset($color_code) && !empty($color_code)) {
                        $color_code = explode(',', $color_code);
                        $text_color = $color_code[0];
                        $back_ground_color = $color_code[1];
                    } else {
                        $text_color = "000000";
                        $back_ground_color = "ffb80c";
                    }

                    $text_color = '#' . $text_color;
                    $back_ground_color = '#' . $back_ground_color;


                    ?>
                    <style>
                        .frm_upd {
                            width: 100%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;
                        }

                        .sb {
                            width: 100%;
                            background-color: #4CAF50;
                            color: white;
                            padding: 14px 20px;
                            margin: 8px 0;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                        }

                        .sb:hover {
                            background-color: #45a049;
                        }

                    </style>
                    <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                        <h3>Update Color Code <?php echo $res[0]->sport; ?></h3>
                        <hr/>
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <strong>Text Color</strong>
                                </td>
                                <td>
                                    <input type="color" id="text_color" name="text_color"
                                           value="<?php echo $text_color; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Background Color</strong>
                                </td>
                                <td>
                                    <input type="color" id="back_ground_color" name="back_ground_color"
                                           value="<?php echo $back_ground_color; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="sb" onclick="return generate_color_code();">Update Color</button>
                                </td>
                            </tr>
                        </table>


                        <br>
                    </div>
                    <hr/>
                    <script>
                        function generate_color_code() {
                            col = [];
                            var text_color = $("#text_color").val();
                            text_color = text_color.replace('#', '');
                            col.push(text_color);
                            var back_ground_color = $("#back_ground_color").val();
                            back_ground_color = back_ground_color.replace('#', '');
                            col.push(back_ground_color);

                            var upd_col = col.join();
                            console.log('upd_col ', upd_col);

                            window.location.href = encodeURI("?page=<?php echo $_GET['page']; ?>&val=<?php echo $id; ?>&act=color_code_upd&cols=" + upd_col);
                        }
                    </script>
                    <?php
                } ?>

                <?php if (isset($_GET['val']) && $_GET['act'] == 'upd') {
                    $id = $_GET['val'];
                    $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");

                    ?>
                    <style>
                        .frm_upd {
                            width: 100%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;
                        }

                        .sb {
                            width: 100%;
                            background-color: #4CAF50;
                            color: white;
                            padding: 14px 20px;
                            margin: 8px 0;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                        }

                        .sb:hover {
                            background-color: #45a049;
                        }

                    </style>
                    <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                        <h3>Update <?php echo $res[0]->sport; ?></h3>
                        <hr/>
                        <form action="<?php echo admin_url('admin.php'); ?>" method="post">
                            <label for="sport"><b>Sport</b></label>
                            <input type="text" value="<?php echo $res[0]->Sport; ?>" class="frm_upd" id="sport"
                                   name="sport"
                                   placeholder="Sport..">

                            <label for=""><b>Event ID</b></label>
                            <input type="text" value="<?php echo $res[0]->EventID; ?>" class="frm_upd" name="event_id"/>

                            <label for=""><b>Team</b></label>
                            <input type="text" value="<?php echo $res[0]->Team; ?>" class="frm_upd" name="team"/>

                            <label for=""><b>Win</b></label>
                            <input type="text" value="<?php echo $res[0]->Win; ?>" class="frm_upd" name="win"/>

                            <label for=""><b>Place</b></label>
                            <input type="text" value="<?php echo $res[0]->Place; ?>" class="frm_upd" name="place"/>

                            <label for=""><b>Eliminated</b></label>
                            <input type="text" value="<?php echo $res[0]->Eliminated; ?>" class="frm_upd"
                                   name="eliminated"/>

                            <label for=""><b>BookMakerLink</b></label>
                            <input type="text" value="<?php echo $res[0]->BookMakerLink; ?>" class="frm_upd"
                                   name="BookMakerLink"/>


                            <input type="hidden" name="id" value="<?php echo $res[0]->id; ?>"/>
                            <input type="hidden" name="action" value="wpse10500"/>
                            <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">

                            <input type="submit" class="sb" value="Submit">
                        </form>
                    </div>
                    <hr/>
                    <?php
                } ?>

                <?php if (isset($_GET['val']) && $_GET['act'] == 'summary') {
                    $id = $_GET['val'];
                    $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                    ?>
                    <style>
                        .frm_upd {
                            width: 100%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;
                        }

                        .sb {
                            width: 100%;
                            background-color: #4CAF50;
                            color: white;
                            padding: 14px 20px;
                            margin: 8px 0;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                        }

                        .sb:hover {
                            background-color: #45a049;
                        }

                    </style>
                    <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">
                        <h3>Clicks Summary <?php echo $res[0]->sport; ?></h3>
                        <hr/>
                        <table>
                            <tr>
                                <td><strong>Clicks</strong></td>
                                <td><?php echo $res[0]->clicks; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Info</strong></td>
                                <td><?php echo $res[0]->state; ?></td>
                            </tr>
                        </table>
                        <a href="admin.php?page=xml-feeder-australian-rules"> <input type="Button" class="sb"
                                                                                     value="Back"></a>
                    </div>
                    <hr/>
                    <?php
                } ?>

                <?php

                if (isset($_GET['val']) && $_GET['act'] == 'del') {
                    global $wpdb;
                    $table_name = $wpdb->prefix . "xmlfeeds_events";
                    $id = $_GET['val'];

                    $sql = "DELETE FROM $table_name WHERE id= $id";
                    $wpdb->query($sql);

                    $_SESSION['msg_1'] = 'Record deleted successfully ';
                    echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                    exit;
                }

                if (isset($_GET['val']) && $_GET['act'] == 'status_update') {
                    global $wpdb;
                    $table_name = $wpdb->prefix . "xmlfeeds_events";
                    $id = $_GET['val'];
                    $status = $_GET['status'];

                    $sql = "UPDATE $table_name SET status = $status WHERE id= $id";
                    $wpdb->query($sql);

                    $_SESSION['msg_1'] = 'Status updated successfully ';
                    echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                    exit;
                }

                ?>

                <?php
                require_once('pagination.class.php');

                $count_sql = '';

                if (!empty($search_term)) {

                    $search_term = explode('::', $search_term);
                    $column = $search_term[0];
                    $search_type = $search_term[1];

                    $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 3 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC";
                } else {
                    $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 3 ORDER BY id ASC";
                }

                $items = count($wpdb->get_results($count_sql));


                if ($items > 0) {
                    $p = new pagination;
                    $p->items($items);
                    $p->limit(30); // Limit entries per page
                    $p->target("admin.php?page=" . $_GET['page']);
                    $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
                    $p->calculate(); // Calculates what to show
                    $p->parameterName('paging');
                    $p->adjacents(1); //No. of page away from the current page

                    if (!isset($_GET['paging'])) {
                        $p->page = 1;
                    } else {
                        $p->page = $_GET['paging'];
                    }

                    //Query for limit paging
                    $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

                } else {
                    echo "No Record Found";
                }

                ?>
                <style>
                    .pagination {
                        display: inline-block;
                    }

                    .pagination a {
                        color: black;
                        float: left;
                        padding: 8px 16px;
                        text-decoration: none;
                        transition: background-color .3s;
                        border: 1px solid #ddd;
                    }

                    .active {
                        background-color: #4CAF50;
                        color: white;
                        border: 1px solid #4CAF50;
                    }

                    .pagination a:hover:not(.active) {
                        background-color: #ddd;
                    }

                    #customers {
                        font-family: Arial, Helvetica, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #customers td, #customers th {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }

                    #customers tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }

                    #customers tr:hover {
                        background-color: #ddd;
                    }

                    #customers th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        background-color: #4CAF50;
                        color: white;
                    }
                </style>

                <div class="wrap">
                    <h2> <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                            echo "Recently Updated";
                        } ?> Greyhound Racing XML Feeds <a
                                href="?page=<?php echo $_GET['page']; ?>&act=recently_updated_records"
                                style="margin-left: 2%; font-size: small;">Recently Updated</a></h2>


                    <div class="tablenav">

                        <?php
                        $search_term = '';
                        if (isset($_GET['search_term'])) {
                            $search_term = $_GET['search_term'];

                            $search_term = explode('::', $search_term);
                            $column = $search_term[0];
                            $search_type = $search_term[1];
                        }

                        ?>

                        <input type="text" id="search2" style="margin:auto;max-width:300px"
                               placeholder="Search Sports..."
                               name="search2" value="<?php if (isset($search_type)) {
                            echo $search_type;
                        } ?>">
                        <select name="search_type" id="search_type" style="height: 28px;margin-bottom: 3px;">
                            <option <?php if (isset($column) && $column == 'EventID') { ?> selected <?php } ?>
                                    value="EventID">EventID
                            </option>
                            <option <?php if (isset($column) && $column == 'Description') { ?> selected <?php } ?>
                                    value="Description">Description
                            </option>
                            <option <?php if (isset($column) && $column == 'Meeting') { ?> selected <?php } ?>
                                    value="OutcomeAt">Meeting
                            </option>
                            <option <?php if (isset($column) && $column == 'RaceNum') { ?> selected <?php } ?>
                                    value="RaceNum">RaceNum
                            </option>
                            <option <?php if (isset($column) && $column == 'OutcomeAt') { ?> selected <?php } ?>
                                    value="OutcomeAt">OutcomeAt
                            </option>
                            <option <?php if (isset($column) && $column == 'SuspendAt') { ?> selected <?php } ?>
                                    value="SuspendAt">SuspendAt
                            </option>
                            <option <?php if (isset($column) && $column == 'Num') { ?> selected <?php } ?> value="Num">
                                Num
                            </option>
                            <option <?php if (isset($column) && $column == 'Team') { ?> selected <?php } ?>value="Team">
                                Team
                            </option>
                            <option <?php if (isset($column) && $column == 'Win') { ?> selected <?php } ?> value="Win">
                                Win
                            </option>
                            <option <?php if (isset($column) && $column == 'Place') { ?> selected <?php } ?>
                                    value="Place">ALine
                            </option>
                            <option <?php if (isset($column) && $column == 'Eliminated') { ?> selected <?php } ?>
                                    value="Eliminated">Eliminated
                            </option>
                        </select>
                        <button type="button" onclick="return do_search();" style="height: 28px;">Search</button>

                        <script>
                            var page = "<?php echo $_GET['page']; ?>";

                            function do_search() {
                                var val = $('#search2').val();
                                var search_type = $('#search_type option:selected').text();
                                if (!val) {
                                    alert("Search Term Required!");
                                    return false;
                                }

                                val = search_type + '::' + val;
                                window.location.href = '?page=' + page + '&search_term=' + val;
                            }
                        </script>
                        <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                        } else { ?>
                            <div class='tablenav-pages'>
                                <?php echo $p->show();  // Echo out the list of paging.
                                ?>
                            </div>
                        <?php } ?>

                    </div>
                    <style>
                        .Container1 {
                            width: 100%;
                            /*overflow-y: auto;*/
                            overflow: auto;
                            height: 550px;
                        }

                        .Content1 {
                            width: 100%;
                        }

                        .Flipped, .Flipped .Content1 {
                            transform: rotateX(359deg);
                            -ms-transform: rotateX(359deg);
                            -webkit-transform: rotateX(359deg);
                        }

                        th {
                            position: -webkit-sticky;
                            position: sticky;
                            top: 0;
                            z-index: 2;
                        }

                        th[scope=row] {
                            position: -webkit-sticky;
                            position: sticky;
                            left: 0;
                            z-index: 1;
                        }
                    </style>

                    <div class="Container1 Flipped">
                        <div class="Content1">
                            <table id="customers" class="widefat">
                                <thead>
                                <tr>
                                    <th>Sport</th>
                                    <th>EventID</th>
                                    <th>Description</th>
                                    <th>Meeting</th>
                                    <th>RaceNum</th>
                                    <th>OutcomeAt</th>
                                    <th>SuspendAt</th>
                                    <th>Num</th>
                                    <th>Team</th>
                                    <th>Win</th>
                                    <th>Place</th>
                                    <th>Eliminated</th>
                                    <th>Color Code</th>
                                    <th>Clicks Summary</th>
                                    <th>Short Code</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                    <th>Action's</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $search_term = '';
                                if (isset($_GET['search_term'])) {
                                    $search_term = $_GET['search_term'];
                                }

                                if (!empty($search_term)) {

                                    $search_term = explode('::', $search_term);
                                    $column = $search_term[0];
                                    $search_type = $search_term[1];

                                    $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 3 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC $limit";
                                } else {
                                    $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 3 ORDER BY id ASC $limit";
                                }

                                if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                                    $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 3 ORDER BY updated_at DESC LIMIT 50";
                                }

                                $result = $wpdb->get_results($res_sql);

                                ?>
                                <?php foreach ((array)$result as $val) { ?>
                                    <tr>
                                        <td><?php echo $val->Sport; ?></td>
                                        <td><?php echo $val->EventID; ?></td>
                                        <td><?php echo $val->Description; ?></td>
                                        <td><?php echo $val->Meeting; ?></td>
                                        <td><?php echo $val->RaceNum; ?></td>
                                        <td><?php echo $val->OutcomeAt; ?></td>
                                        <td><?php echo $val->SuspendAt; ?></td>
                                        <td><?php echo $val->Num; ?></td>
                                        <td><?php echo $val->Team; ?></td>
                                        <td><?php echo $val->Win; ?></td>
                                        <td><?php echo $val->Place; ?></td>
                                        <td><?php echo $val->Eliminated; ?></td>
                                        <td>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=color_code">Update
                                                Colors</a></td>
                                        <td>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=summary">Summary</a>
                                        </td>

                                        <td>[xmlfdr id="<?php echo $val->id; ?>"]</td>
                                        <td>
                                            <?php if ($val->status == 0) {
                                                echo "Active";
                                            } else {
                                                echo "In Active";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($val->status == 0) {
                                                ?>
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=1&act=status_update"
                                                   class="confirmation">Deactivate</a>
                                                <?php
                                            } ?>
                                            <?php if ($val->status == 1) {
                                                ?>
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=0&act=status_update"
                                                   class="confirmation">Activate</a>
                                                <?php
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=upd">Update</a>
                                            |
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=del"
                                               class="confirmation">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
                <script type="text/javascript">
                    var elems = document.getElementsByClassName('confirmation');
                    var confirmIt = function (e) {
                        if (!confirm('Are you sure?')) e.preventDefault();
                    };
                    for (var i = 0, l = elems.length; i < l; i++) {
                        elems[i].addEventListener('click', confirmIt, false);
                    }
                </script>
                <?php
                }

                function xml_feeder_manage_harness_racing_func3()
                {

                global $wpdb;
                $table_name = $wpdb->prefix . "xmlfeeds_events";

                $search_term = '';
                if (isset($_GET['search_term'])) {
                    $search_term = $_GET['search_term'];
                }


                ?>
                <script>
                    $('.wp-first-item').hide();
                </script>
                <style>
                    #example_filter {
                        margin-right: 2% !important;
                    }

                    .container_class {
                        margin-top: 2%;
                    }
                </style>
                <img src="<?php echo plugin_dir_url(__FILE__) ?>assets/img/ajax-loader.gif" id="loader"
                     style="display: none;">
                <div class="container_class">

                    <?php if (isset($_SESSION['msg_1'])) { ?>
                        <style>
                            .alert {
                                padding: 20px;
                                background-color: #04AA6D;
                                color: white;
                                width: 96%;
                                margin-bottom: 2%;
                            }

                            .closebtn {
                                margin-left: 15px;
                                color: white;
                                font-weight: bold;
                                float: right;
                                font-size: 22px;
                                line-height: 20px;
                                cursor: pointer;
                                transition: 0.3s;
                            }

                            .closebtn:hover {
                                color: black;
                            }
                        </style>

                        <div class="alert">
                            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                            unset($_SESSION['msg_1']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code_upd') {
                        global $wpdb;
                        $table_name = $wpdb->prefix . "xmlfeeds_events";
                        $cols = $_GET['cols'];
                        $id = $_GET['val'];

                        $sql = "UPDATE  $table_name SET color_code = '" . $cols . "' WHERE id= $id";
                        $wpdb->query($sql);

                        $_SESSION['msg_1'] = 'Record Updated successfully ';
                        echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                        exit;
                    } ?>


                    <?php if (isset($_GET['val']) && $_GET['act'] == 'color_code') {
                        $id = $_GET['val'];
                        $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                        $color_code = $res[0]->color_code;

                        if (isset($color_code) && !empty($color_code)) {
                            $color_code = explode(',', $color_code);
                            $text_color = $color_code[0];
                            $back_ground_color = $color_code[1];
                        } else {
                            $text_color = "000000";
                            $back_ground_color = "ffb80c";
                        }

                        $text_color = '#' . $text_color;
                        $back_ground_color = '#' . $back_ground_color;


                        ?>
                        <style>
                            .frm_upd {
                                width: 100%;
                                padding: 12px 20px;
                                margin: 8px 0;
                                display: inline-block;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                                box-sizing: border-box;
                            }

                            .sb {
                                width: 100%;
                                background-color: #4CAF50;
                                color: white;
                                padding: 14px 20px;
                                margin: 8px 0;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            .sb:hover {
                                background-color: #45a049;
                            }
                        </style>
                        <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                            <h3>Update Color Code <?php echo $res[0]->sport; ?></h3>
                            <hr/>
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <strong>Text Color</strong>
                                    </td>
                                    <td>
                                        <input type="color" id="text_color" name="text_color"
                                               value="<?php echo $text_color; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Background Color</strong>
                                    </td>
                                    <td>
                                        <input type="color" id="back_ground_color" name="back_ground_color"
                                               value="<?php echo $back_ground_color; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="sb" onclick="return generate_color_code();">Update Color</button>
                                    </td>
                                </tr>
                            </table>


                            <br>
                        </div>
                        <hr/>
                        <script>
                            function generate_color_code() {
                                col = [];
                                var text_color = $("#text_color").val();
                                text_color = text_color.replace('#', '');
                                col.push(text_color);
                                var back_ground_color = $("#back_ground_color").val();
                                back_ground_color = back_ground_color.replace('#', '');
                                col.push(back_ground_color);

                                var upd_col = col.join();
                                console.log('upd_col ', upd_col);

                                window.location.href = encodeURI("?page=<?php echo $_GET['page']; ?>&val=<?php echo $id; ?>&act=color_code_upd&cols=" + upd_col);
                            }
                        </script>
                        <?php
                    } ?>

                    <?php if (isset($_GET['val']) && $_GET['act'] == 'upd') {
                        $id = $_GET['val'];
                        $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");

                        ?>
                        <style>
                            .frm_upd {
                                width: 100%;
                                padding: 12px 20px;
                                margin: 8px 0;
                                display: inline-block;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                                box-sizing: border-box;
                            }

                            .sb {
                                width: 100%;
                                background-color: #4CAF50;
                                color: white;
                                padding: 14px 20px;
                                margin: 8px 0;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            .sb:hover {
                                background-color: #45a049;
                            }

                        </style>
                        <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                            <h3>Update <?php echo $res[0]->sport; ?></h3>
                            <hr/>
                            <form action="<?php echo admin_url('admin.php'); ?>" method="post">
                                <label for="sport"><b>Sport</b></label>
                                <input type="text" value="<?php echo $res[0]->Sport; ?>" class="frm_upd" id="sport"
                                       name="sport"
                                       placeholder="Sport..">

                                <label for=""><b>Event ID</b></label>
                                <input type="text" value="<?php echo $res[0]->EventID; ?>" class="frm_upd"
                                       name="event_id"/>

                                <label for=""><b>Team</b></label>
                                <input type="text" value="<?php echo $res[0]->Team; ?>" class="frm_upd" name="team"/>

                                <label for=""><b>Win</b></label>
                                <input type="text" value="<?php echo $res[0]->Win; ?>" class="frm_upd" name="win"/>

                                <label for=""><b>Place</b></label>
                                <input type="text" value="<?php echo $res[0]->Place; ?>" class="frm_upd" name="place"/>

                                <label for=""><b>Eliminated</b></label>
                                <input type="text" value="<?php echo $res[0]->Eliminated; ?>" class="frm_upd"
                                       name="eliminated"/>

                                <label for=""><b>BookMakerLink</b></label>
                                <input type="text" value="<?php echo $res[0]->BookMakerLink; ?>" class="frm_upd"
                                       name="BookMakerLink"/>


                                <input type="hidden" name="id" value="<?php echo $res[0]->id; ?>"/>
                                <input type="hidden" name="action" value="wpse10500"/>
                                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">

                                <input type="submit" class="sb" value="Submit">
                            </form>
                        </div>
                        <hr/>
                        <?php
                    } ?>

                    <?php if (isset($_GET['val']) && $_GET['act'] == 'summary') {
                        $id = $_GET['val'];
                        $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");
                        ?>
                        <style>
                            .frm_upd {
                                width: 100%;
                                padding: 12px 20px;
                                margin: 8px 0;
                                display: inline-block;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                                box-sizing: border-box;
                            }

                            .sb {
                                width: 100%;
                                background-color: #4CAF50;
                                color: white;
                                padding: 14px 20px;
                                margin: 8px 0;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            .sb:hover {
                                background-color: #45a049;
                            }

                        </style>
                        <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">
                            <h3>Clicks Summary <?php echo $res[0]->sport; ?></h3>
                            <hr/>
                            <table>
                                <tr>
                                    <td><strong>Clicks</strong></td>
                                    <td><?php echo $res[0]->clicks; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Info</strong></td>
                                    <td><?php echo $res[0]->state; ?></td>
                                </tr>
                            </table>
                            <a href="admin.php?page=xml-feeder-australian-rules"> <input type="Button" class="sb"
                                                                                         value="Back"></a>
                        </div>
                        <hr/>
                        <?php
                    } ?>

                    <?php

                    if (isset($_GET['val']) && $_GET['act'] == 'del') {
                        global $wpdb;
                        $table_name = $wpdb->prefix . "xmlfeeds_events";
                        $id = $_GET['val'];

                        $sql = "DELETE FROM $table_name WHERE id= $id";
                        $wpdb->query($sql);

                        $_SESSION['msg_1'] = 'Record deleted successfully ';
                        echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                        exit;
                    }

                    if (isset($_GET['val']) && $_GET['act'] == 'status_update') {
                        global $wpdb;
                        $table_name = $wpdb->prefix . "xmlfeeds_events";
                        $id = $_GET['val'];
                        $status = $_GET['status'];

                        $sql = "UPDATE $table_name SET status = $status WHERE id= $id";
                        $wpdb->query($sql);

                        $_SESSION['msg_1'] = 'Status updated successfully ';
                        echo "<script>window.location='?page=" . $_GET['page'] . "'</script>";
                        exit;
                    }

                    ?>

                    <?php
                    require_once('pagination.class.php');

                    $count_sql = '';

                    if (!empty($search_term)) {

                        $search_term = explode('::', $search_term);
                        $column = $search_term[0];
                        $search_type = $search_term[1];

                        $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 4 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC";
                    } else {
                        $count_sql = "SELECT * FROM  $table_name WHERE `sportType` = 4 ORDER BY id ASC";
                    }

                    $items = count($wpdb->get_results($count_sql));


                    if ($items > 0) {
                        $p = new pagination;
                        $p->items($items);
                        $p->limit(30); // Limit entries per page
                        $p->target("admin.php?page=" . $_GET['page']);
                        $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
                        $p->calculate(); // Calculates what to show
                        $p->parameterName('paging');
                        $p->adjacents(1); //No. of page away from the current page

                        if (!isset($_GET['paging'])) {
                            $p->page = 1;
                        } else {
                            $p->page = $_GET['paging'];
                        }

                        //Query for limit paging
                        $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

                    } else {
                        echo "No Record Found";
                    }

                    ?>
                    <style>
                        .pagination {
                            display: inline-block;
                        }

                        .pagination a {
                            color: black;
                            float: left;
                            padding: 8px 16px;
                            text-decoration: none;
                            transition: background-color .3s;
                            border: 1px solid #ddd;
                        }

                        .active {
                            background-color: #4CAF50;
                            color: white;
                            border: 1px solid #4CAF50;
                        }

                        .pagination a:hover:not(.active) {
                            background-color: #ddd;
                        }

                        #customers {
                            font-family: Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                            width: 100%;
                        }

                        #customers td, #customers th {
                            border: 1px solid #ddd;
                            padding: 8px;
                        }

                        #customers tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }

                        #customers tr:hover {
                            background-color: #ddd;
                        }

                        #customers th {
                            padding-top: 12px;
                            padding-bottom: 12px;
                            text-align: left;
                            background-color: #4CAF50;
                            color: white;
                        }
                    </style>

                    <div class="wrap">
                        <h2> <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                                echo "Recently Updated";
                            } ?> Harness Racing XML Feeds <a
                                    href="?page=<?php echo $_GET['page']; ?>&act=recently_updated_records"
                                    style="margin-left: 2%; font-size: small;">Recently Updated</a></h2>


                        <div class="tablenav">

                            <?php
                            $search_term = '';
                            if (isset($_GET['search_term'])) {
                                $search_term = $_GET['search_term'];

                                $search_term = explode('::', $search_term);
                                $column = $search_term[0];
                                $search_type = $search_term[1];
                            }

                            ?>

                            <input type="text" id="search2" style="margin:auto;max-width:300px"
                                   placeholder="Search Sports..."
                                   name="search2" value="<?php if (isset($search_type)) {
                                echo $search_type;
                            } ?>">
                            <select name="search_type" id="search_type" style="height: 28px;margin-bottom: 3px;">
                                <option <?php if (isset($column) && $column == 'EventID') { ?> selected <?php } ?>
                                        value="EventID">EventID
                                </option>
                                <option <?php if (isset($column) && $column == 'Description') { ?> selected <?php } ?>
                                        value="Description">Description
                                </option>
                                <option <?php if (isset($column) && $column == 'Meeting') { ?> selected <?php } ?>
                                        value="OutcomeAt">Meeting
                                </option>
                                <option <?php if (isset($column) && $column == 'RaceNum') { ?> selected <?php } ?>
                                        value="RaceNum">RaceNum
                                </option>
                                <option <?php if (isset($column) && $column == 'OutcomeAt') { ?> selected <?php } ?>
                                        value="OutcomeAt">OutcomeAt
                                </option>
                                <option <?php if (isset($column) && $column == 'SuspendAt') { ?> selected <?php } ?>
                                        value="SuspendAt">SuspendAt
                                </option>
                                <option <?php if (isset($column) && $column == 'Num') { ?> selected <?php } ?>
                                        value="Num">Num
                                </option>
                                <option <?php if (isset($column) && $column == 'Team') { ?> selected <?php } ?>
                                        value="Team">Team
                                </option>
                                <option <?php if (isset($column) && $column == 'Win') { ?> selected <?php } ?>
                                        value="Win">Win
                                </option>
                                <option <?php if (isset($column) && $column == 'Place') { ?> selected <?php } ?>
                                        value="Place">ALine
                                </option>
                                <option <?php if (isset($column) && $column == 'Eliminated') { ?> selected <?php } ?>
                                        value="Eliminated">Eliminated
                                </option>
                            </select>
                            <button type="button" onclick="return do_search();" style="height: 28px;">Search</button>

                            <script>
                                var page = "<?php echo $_GET['page']; ?>";

                                function do_search() {
                                    var val = $('#search2').val();
                                    var search_type = $('#search_type option:selected').text();
                                    if (!val) {
                                        alert("Search Term Required!");
                                        return false;
                                    }

                                    val = search_type + '::' + val;
                                    window.location.href = '?page=' + page + '&search_term=' + val;
                                }
                            </script>
                            <?php if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                            } else { ?>
                                <div class='tablenav-pages'>
                                    <?php echo $p->show();  // Echo out the list of paging.
                                    ?>
                                </div>
                            <?php } ?>
                        </div>

                        <style>
                            .Container1 {
                                width: 100%;
                                /*overflow-y: auto;*/
                                overflow: auto;
                                height: 550px;
                            }

                            .Content1 {
                                width: 100%;
                            }

                            .Flipped, .Flipped .Content1 {
                                transform: rotateX(359deg);
                                -ms-transform: rotateX(359deg);
                                -webkit-transform: rotateX(359deg);
                            }

                            th {
                                position: -webkit-sticky;
                                position: sticky;
                                top: 0;
                                z-index: 2;
                            }

                            th[scope=row] {
                                position: -webkit-sticky;
                                position: sticky;
                                left: 0;
                                z-index: 1;
                            }
                        </style>

                        <div class="Container1 Flipped">
                            <div class="Content1">

                                <table id="customers" class="widefat">
                                    <thead>
                                    <tr>
                                        <th>Sport</th>
                                        <th>EventID</th>
                                        <th>Description</th>
                                        <th>Meeting</th>
                                        <th>RaceNum</th>
                                        <th>OutcomeAt</th>
                                        <th>SuspendAt</th>
                                        <th>Num</th>
                                        <th>Team</th>
                                        <th>Win</th>
                                        <th>Place</th>
                                        <th>Eliminated</th>
                                        <th>Color Code</th>
                                        <th>Clicks Summary</th>
                                        <th>Short Code</th>
                                        <th>Status</th>
                                        <th>Update Status</th>
                                        <th>Action's</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $search_term = '';
                                    if (isset($_GET['search_term'])) {
                                        $search_term = $_GET['search_term'];
                                    }

                                    if (!empty($search_term)) {

                                        $search_term = explode('::', $search_term);
                                        $column = $search_term[0];
                                        $search_type = $search_term[1];

                                        $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 4 AND $column LIKE '%" . $search_type . "%' ORDER BY id ASC $limit";
                                    } else {
                                        $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 4 ORDER BY id ASC $limit";
                                    }

                                    if (isset($_GET['act']) && $_GET['act'] == 'recently_updated_records') {
                                        $res_sql = "SELECT * FROM  $table_name WHERE `sportType` = 4 ORDER BY updated_at DESC LIMIT 50";
                                    }

                                    $result = $wpdb->get_results($res_sql);

                                    ?>
                                    <?php foreach ((array)$result as $val) { ?>
                                        <tr>
                                            <td><?php echo $val->Sport; ?></td>
                                            <td><?php echo $val->EventID; ?></td>
                                            <td><?php echo $val->Description; ?></td>
                                            <td><?php echo $val->Meeting; ?></td>
                                            <td><?php echo $val->RaceNum; ?></td>
                                            <td><?php echo $val->OutcomeAt; ?></td>
                                            <td><?php echo $val->SuspendAt; ?></td>
                                            <td><?php echo $val->Num; ?></td>
                                            <td><?php echo $val->Team; ?></td>
                                            <td><?php echo $val->Win; ?></td>
                                            <td><?php echo $val->Place; ?></td>
                                            <td><?php echo $val->Eliminated; ?></td>
                                            <td>
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=color_code">Update
                                                    Colors</a></td>
                                            <td>
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=summary">Summary</a>
                                            </td>

                                            <td>[xmlfdr id="<?php echo $val->id; ?>"]</td>
                                            <td>
                                                <?php if ($val->status == 0) {
                                                    echo "Active";
                                                } else {
                                                    echo "In Active";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($val->status == 0) {
                                                    ?>
                                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=1&act=status_update"
                                                       class="confirmation">Deactivate</a>
                                                    <?php
                                                } ?>
                                                <?php if ($val->status == 1) {
                                                    ?>
                                                    <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&status=0&act=status_update"
                                                       class="confirmation">Activate</a>
                                                    <?php
                                                } ?>
                                            </td>
                                            <td>
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=upd">Update</a>
                                                |
                                                <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=del"
                                                   class="confirmation">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                    <script type="text/javascript">
                        var elems = document.getElementsByClassName('confirmation');
                        var confirmIt = function (e) {
                            if (!confirm('Are you sure?')) e.preventDefault();
                        };
                        for (var i = 0, l = elems.length; i < l; i++) {
                            elems[i].addEventListener('click', confirmIt, false);
                        }
                    </script>
                    <?php
                    }


                    /**
                     * Short Code For Plugin
                     */
                    function xmlfdr_short($attr)
                    {

                        $a = shortcode_atts(array(
                            'id' => ''
                        ), $attr);

                        ob_start();

                        global $wpdb;
                        $table_name = $wpdb->prefix . "xmlfeeds_events";


                        if (!empty($a['id'])) {
                            $id = $a['id'];
                            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id AND status = 0");

                        if (!empty($res)) {

                            $color_code = $res[0]->color_code;

                        if ($res[0]->sportType == 1) {// Austrailian sports
                        if (empty($res[0]->short_code)) {
                            echo $res[0]->Sport;
                        } else {
                            $short_code = explode(',', $res[0]->short_code);
                        if (!empty($short_code)) {
                            ?>

                            <a href="<?php echo $res[0]->BookMakerLink; ?>"
                               onclick="return BookMakerLinkClick();"
                               target="_blank" style="text-decoration: none;">

                                <?php
                                if (isset($color_code) && !empty($color_code)) {
                                    $color_code = explode(',', $color_code);
                                    $text_color = $color_code[0];
                                    $back_ground_color = $color_code[1];
                                } else {
                                    $text_color = "000000";
                                    $back_ground_color = "ffb80c";
                                }

                                $text_color = '#' . $text_color;
                                $back_ground_color = '#' . $back_ground_color;

                                ?>

                                <?php foreach ($short_code as $item) {
                                    ?>
                                    <b style="
                                            background-color: <?php echo $back_ground_color; ?>;
                                            color: <?php echo $text_color; ?>;
                                            display: inline-flex;
                                            overflow: hidden;
                                            text-decoration: none;
                                            padding-left: 4px;
                                            padding-right: 4px; ">
                                        <?php echo number_format((float)$res[0]->$item, 2, '.', ''); ?>
                                    </b>
                                    <?php
                                } ?>

                            </a>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                            <script type="text/javascript">
                                function BookMakerLinkClick() {
                                    var id = "<?php echo $id; ?>";
                                    $.getJSON('http://ip-api.com/json', function (data) {
                                        var state = JSON.stringify(data, null, 2);

                                        var ajax_url = "<?php echo esc_url_raw(admin_url('admin-ajax.php')); ?>";
                                        $.post(
                                            ajax_url,
                                            {
                                                data: {id: id, state: state},
                                                action: 'et_test_plugin_create_post'
                                            },
                                            function (res) {
                                                //console.log(`Response ----> `, res)

                                            }
                                        );

                                        return false;
                                    });

                                }
                            </script>

                        <?php
                        }
                        }
                        } else {
                        ?>

                        <?php if (!empty($res[0]->BookMakerLink)) { ?>
                        <?php
                        if (isset($color_code) && !empty($color_code)) {
                            $color_code = explode(',', $color_code);
                            $text_color = $color_code[0];
                            $back_ground_color = $color_code[1];
                        } else {
                            $text_color = "000000";
                            $back_ground_color = "ffb80c";
                        }

                        $text_color = '#' . $text_color;
                        $back_ground_color = '#' . $back_ground_color;

                        ?>

                            <a href="<?php echo $res[0]->BookMakerLink; ?>"
                               onclick="return BookMakerLinkClick();"
                               target="_blank" style="text-decoration: none;">
                                <b style="
                                        background-color: <?php echo $back_ground_color; ?>;
                                        color: <?php echo $text_color; ?>;
                                        display: inline-flex;
                                        overflow: hidden;
                                        text-decoration: none;
                                        padding-left: 4px;
                                        padding-right: 4px;
                                        ">
                                    <?php echo number_format((float)$res[0]->Win, 2, '.', ''); ?>

                                </b></a>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                            <script type="text/javascript">
                                function BookMakerLinkClick() {
                                    var id = "<?php echo $id; ?>";
                                    $.getJSON('http://ip-api.com/json', function (data) {
                                        var state = JSON.stringify(data, null, 2);

                                        var ajax_url = "<?php echo esc_url_raw(admin_url('admin-ajax.php')); ?>";
                                        $.post(
                                            ajax_url,
                                            {
                                                data: {id: id, state: state},
                                                action: 'et_test_plugin_create_post'
                                            },
                                            function (res) {
                                                //console.log(`Response ----> `, res)

                                            }
                                        );

                                        return false;
                                    });

                                }
                            </script>
                        <?php } else {
                        ?>
                        <?php
                        if (isset($color_code) && !empty($color_code)) {
                            $color_code = explode(',', $color_code);
                            $text_color = $color_code[0];
                            $back_ground_color = $color_code[1];
                        } else {
                            $text_color = "000000";
                            $back_ground_color = "ffb80c";
                        }

                        $text_color = '#' . $text_color;
                        $back_ground_color = '#' . $back_ground_color;

                        ?>
                            <b style="
                                    background-color: <?php echo $back_ground_color; ?>;
                                    color: <?php echo $text_color; ?>;
                                    display: inline-flex;
                                    overflow: hidden;
                                    text-decoration: none;
                                    padding-left: 4px;
                                    padding-right: 4px;
                                    ">
                                <?php echo number_format((float)$res[0]->Win, 2, '.', ''); ?>

                            </b>
                        <?php
                        // echo "<b style='background-color: #ffb80c; display: inline-flex; width: 45px; overflow: hidden; text-decoration: none;'>" .  number_format((float)$res[0]->Win, 2, '.', '') . "</b>";
                        } ?>
                        <?php
                        }
                        } else {

                        $text_color = "000000";
                        $back_ground_color = "ffb80c";

                        $text_color = '#' . $text_color;
                        $back_ground_color = '#' . $back_ground_color;

                        ?>
                            <b style="
                                    background-color: <?php echo $back_ground_color; ?>;
                                    color: <?php echo $text_color; ?>;
                                    display: inline-flex;
                                    overflow: hidden;
                                    text-decoration: none;
                                    padding-left: 4px;
                                    padding-right: 4px; ">
                                0.00
                            </b>
                        <?php

                        }

                        } else {

                        $text_color = "000000";
                        $back_ground_color = "ffb80c";

                        $text_color = '#' . $text_color;
                        $back_ground_color = '#' . $back_ground_color;

                        ?>
                            <b style="
                                    background-color: <?php echo $back_ground_color; ?>;
                                    color: <?php echo $text_color; ?>;
                                    display: inline-flex;
                                    overflow: hidden;
                                    text-decoration: none;
                                    padding-left: 4px;
                                    padding-right: 4px; ">
                                0.00
                            </b>
                            <?php
                        }


                        return ob_get_clean();
                    }

                    add_shortcode('xmlfdr', 'xmlfdr_short');

                    function et_test_plugin_create_post()
                    {
                        if (isset($_POST['data'])) {

                            $id = $_POST['data']['id'];
                            $state = json_decode(stripslashes($_POST['data']['state']), true);
                            global $wpdb;
                            $table_name = $wpdb->prefix . "xmlfeeds_events";

                            $sql = "SELECT * FROM $table_name WHERE id = $id";
                            $resp = $wpdb->get_results($sql);
                            $clicks = intval($resp[0]->clicks) + intval(1);

                            $state = '
                                city: ' . $state['city'] . '<br>
                                country: ' . $state['country'] . '<br>
                                isp: ' . $state['isp'] . '<br>
                                isp: ' . $state['isp'] . '<br>
                                lon: ' . $state['lon'] . '<br>
                                org: ' . $state['org'] . '<br>
                                query: ' . $state['query'] . '<br>
                                region: ' . $state['region'] . '<br>
                                regionName: ' . $state['regionName'] . '<br>
                                status: ' . $state['status'] . '<br>
                                zip: ' . $state['zip'];

                            $sql_2 = "UPDATE $table_name SET clicks = '" . $clicks . "', state = '" . $state . "' WHERE id = $id ; ";
                            $q_res = $wpdb->query($sql_2);

                            wp_send_json_success(array(
                                'message' => 'Added successfully!',
                                //'id' => $_POST['data']['id'],
                                //'state' => json_decode(stripslashes($_POST['data']['state']), true),
                                //'Res' => $sql_2,
                                //'q_res' => $q_res,
                            ));
                        }

                        wp_send_json_error(array(
                            'message' => 'Something went wrong!',
                        ));
                    }

                    add_action('wp_ajax_et_test_plugin_create_post', 'et_test_plugin_create_post');
                    add_action('wp_ajax_nopriv_et_test_plugin_create_post', 'et_test_plugin_create_post');


                    add_action('admin_action_wpse10500', 'wpse10500_admin_action');
                    function wpse10500_admin_action()
                    {

                        global $wpdb;
                        $table_name = $wpdb->prefix . "xmlfeeds_events";

                        $id = $_POST['id'];
                        $sport = $_POST['sport'];
                        $event_id = $_POST['event_id'];
                        $team = $_POST['team'];
                        $win = $_POST['win'];
                        $place = $_POST['place'];
                        $eliminated = $_POST['eliminated'];
                        $page = $_POST['page'];
                        $BookMakerLink = $_POST['BookMakerLink'];


                        $sql = "UPDATE  $table_name SET Sport='" . $sport . "', EventID = '" . $event_id . "', Team = '" . $team . "', Win = '" . $win . "', Place = '" . $place . "', Eliminated = '" . $eliminated . "' , BookMakerLink = '" . $BookMakerLink . "' WHERE id = $id";

                        $wpdb->query($sql);

                        $_SESSION['msg_1'] = 'Record updated successfully ';

                        echo "<script>window.location='?page=" . $page . "'</script>";
                        exit();
                    }


                    function xml_feeder_func()
                    {

                    global $wpdb;
                    $table_name = $wpdb->prefix . "xmlfeeds_events";

                    ?>
                    <script>window.location.href = '?page=xml-feeder-australian-rules'</script>

                    <style>
                        #example_filter {
                            margin-right: 2% !important;
                        }

                        .container_class {
                            margin-top: 2%;
                        }
                    </style>
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>assets/img/ajax-loader.gif" id="loader"
                         style="display: none;">
                    <div class="container_class">

                        <?php if (isset($_SESSION['msg_1'])) { ?>
                            <style>
                                .alert {
                                    padding: 20px;
                                    background-color: #04AA6D;
                                    color: white;
                                    width: 96%;
                                    margin-bottom: 2%;
                                }

                                .closebtn {
                                    margin-left: 15px;
                                    color: white;
                                    font-weight: bold;
                                    float: right;
                                    font-size: 22px;
                                    line-height: 20px;
                                    cursor: pointer;
                                    transition: 0.3s;
                                }

                                .closebtn:hover {
                                    color: black;
                                }
                            </style>

                            <div class="alert">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                <strong>Success!</strong> <?php echo $_SESSION['msg_1'];
                                unset($_SESSION['msg_1']); ?>
                            </div>
                        <?php } ?>

                        <?php if (isset($_GET['val']) && $_GET['act'] == 'upd') {
                            $id = $_GET['val'];
                            $res = $wpdb->get_results(" SELECT * FROM  $table_name WHERE id = $id");

                            ?>
                            <style>
                                .frm_upd {
                                    width: 100%;
                                    padding: 12px 20px;
                                    margin: 8px 0;
                                    display: inline-block;
                                    border: 1px solid #ccc;
                                    border-radius: 4px;
                                    box-sizing: border-box;
                                }

                                .sb {
                                    width: 100%;
                                    background-color: #4CAF50;
                                    color: white;
                                    padding: 14px 20px;
                                    margin: 8px 0;
                                    border: none;
                                    border-radius: 4px;
                                    cursor: pointer;
                                }

                                .sb:hover {
                                    background-color: #45a049;
                                }

                            </style>
                            <div class="upd_form" style="width: 50%;margin-left: 15%; margin-bottom: 2%;">

                                <h3>Update <?php echo $res[0]->sport; ?></h3>
                                <hr/>
                                <form action="<?php echo admin_url('admin.php'); ?>" method="post">
                                    <label for="sport"><b>Sport</b></label>
                                    <input type="text" value="<?php echo $res[0]->Sport; ?>" class="frm_upd" id="sport"
                                           name="sport"
                                           placeholder="Sport..">

                                    <label for=""><b>Event ID</b></label>
                                    <input type="text" value="<?php echo $res[0]->EventID; ?>" class="frm_upd"
                                           name="event_id"/>

                                    <label for=""><b>Team</b></label>
                                    <input type="text" value="<?php echo $res[0]->Team; ?>" class="frm_upd"
                                           name="team"/>

                                    <label for=""><b>Win</b></label>
                                    <input type="text" value="<?php echo $res[0]->Win; ?>" class="frm_upd" name="win"/>

                                    <label for=""><b>Place</b></label>
                                    <input type="text" value="<?php echo $res[0]->Place; ?>" class="frm_upd"
                                           name="place"/>

                                    <label for=""><b>Eliminated</b></label>
                                    <input type="text" value="<?php echo $res[0]->Eliminated; ?>" class="frm_upd"
                                           name="eliminated"/>

                                    <input type="hidden" name="id" value="<?php echo $res[0]->id; ?>"/>
                                    <input type="hidden" name="action" value="wpse10500"/>

                                    <input type="submit" class="sb" value="Submit">
                                </form>
                            </div>
                            <hr/>
                            <?php
                        } ?>

                        <?php

                        if (isset($_GET['val']) && $_GET['act'] == 'del') {
                            global $wpdb;
                            $table_name = $wpdb->prefix . "xmlfeeds_events";
                            $id = $_GET['val'];

                            $sql = "DELETE FROM $table_name WHERE id= $id";
                            $wpdb->query($sql);

                            $_SESSION['msg_1'] = 'Record deleted successfully ';
                            echo "<script>window.location='?page=xml-feeder'</script>";
                            exit;
                        }

                        if (isset($_GET['val']) && $_GET['act'] == 'status_update') {
                            global $wpdb;
                            $table_name = $wpdb->prefix . "xmlfeeds_events";
                            $id = $_GET['val'];
                            $status = $_GET['status'];

                            $sql = "UPDATE $table_name SET status = $status WHERE id= $id";
                            $wpdb->query($sql);

                            $_SESSION['msg_1'] = 'Status updated successfully ';
                            echo "<script>window.location='?page=xml-feeder'</script>";
                            exit;
                        }

                        ?>

                        <?php
                        require_once('pagination.class.php');

                        //if (isset($_GET['search_term'])) {
                        $search_term = $_GET['search_term'];
                        $items = count($wpdb->get_results(" SELECT * FROM  $table_name WHERE `Sport` LIKE '%" . $search_term . "%' ORDER BY id ASC"));
                        //    } else {
                        //
                        //        $items = count($wpdb->get_results(" SELECT * FROM  $table_name ORDER BY id ASC"));
                        //    }

                        if ($items > 0) {
                            $p = new pagination;
                            $p->items($items);
                            $p->limit(30); // Limit entries per page
                            $p->target("admin.php?page=" . $_GET['page']);
                            $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
                            $p->calculate(); // Calculates what to show
                            $p->parameterName('paging');
                            $p->adjacents(1); //No. of page away from the current page

                            if (!isset($_GET['paging'])) {
                                $p->page = 1;
                            } else {
                                $p->page = $_GET['paging'];
                            }

                            //Query for limit paging
                            $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

                        } else {
                            echo "No Record Found";
                        }

                        ?>
                        <style>
                            .pagination {
                                display: inline-block;
                            }

                            .pagination a {
                                color: black;
                                float: left;
                                padding: 8px 16px;
                                text-decoration: none;
                                transition: background-color .3s;
                                border: 1px solid #ddd;
                            }

                            .active {
                                background-color: #4CAF50;
                                color: white;
                                border: 1px solid #4CAF50;
                            }

                            .pagination a:hover:not(.active) {
                                background-color: #ddd;
                            }

                            #customers {
                                font-family: Arial, Helvetica, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                            }

                            #customers td, #customers th {
                                border: 1px solid #ddd;
                                padding: 8px;
                            }

                            #customers tr:nth-child(even) {
                                background-color: #f2f2f2;
                            }

                            #customers tr:hover {
                                background-color: #ddd;
                            }

                            #customers th {
                                padding-top: 12px;
                                padding-bottom: 12px;
                                text-align: left;
                                background-color: #4CAF50;
                                color: white;
                            }
                        </style>

                        <div class="wrap">
                            <h2>XML Feeds</h2>


                            <div class="tablenav">

                                <input type="text" id="search2" style="margin:auto;max-width:300px"
                                       placeholder="Search Sports..."
                                       name="search2">
                                <button type="button" onclick="return do_search();" style="height: 28px;">Search
                                </button>
                                <script>
                                    function do_search() {
                                        console.log("I am Searching!");
                                        var val = $('#search2').val();
                                        if (!val) {
                                            alert("Search Sports Name Required!");
                                            return false;
                                        }

                                        window.location.href = '?page=xml-feeder&search_term=' + val;
                                    }
                                </script>
                                <div class='tablenav-pages'>
                                    <?php echo $p->show();  // Echo out the list of paging.
                                    ?>
                                </div>
                            </div>

                            <table id="customers" class="widefat">
                                <thead>
                                <tr>
                                    <th>Sport</th>
                                    <th>EventId</th>
                                    <th>Team</th>
                                    <th>Win</th>
                                    <th>Place</th>
                                    <th>Eliminated</th>
                                    <th>Color Code</th>
                                    <th>Short Code</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                    <th>Action's</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //if (isset($_GET['search_term'])) {
                                $search_term = $_GET['search_term'];
                                $result = $wpdb->get_results(" SELECT * FROM  $table_name WHERE `Sport` LIKE '%" . $search_term . "%' ORDER BY id ASC $limit");
                                //            } else {
                                //                $result = $wpdb->get_results(" SELECT * FROM  $table_name  ORDER BY id ASC $limit");
                                //            }
                                ?>
                                <?php foreach ((array)$result as $val) { ?>
                                    <tr>
                                        <td><?php echo $val->Sport; ?></td>
                                        <td><?php echo $val->EventID; ?></td>
                                        <td><?php echo $val->Team; ?></td>
                                        <td><?php echo $val->Win; ?></td>
                                        <td><?php echo $val->Place; ?></td>
                                        <td><?php echo $val->Eliminated; ?></td>
                                        <td>
                                            <a href="?page=<?php echo $_GET['page']; ?>&val=<?php echo $val->id; ?>&act=color_code">Update
                                                Colors</a></td>
                                        <td>[xmlfdr id="<?php echo $val->id; ?>"]</td>
                                        <td>
                                            <?php if ($val->status == 0) {
                                                echo "Active";
                                            } else {
                                                echo "In Active";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($val->status == 0) {
                                                ?>
                                                <a href="?page=xml-feeder&val=<?php echo $val->id; ?>&status=1&act=status_update"
                                                   class="confirmation">Deactivate</a>
                                                <?php
                                            } ?>
                                            <?php if ($val->status == 1) {
                                                ?>
                                                <a href="?page=xml-feeder&val=<?php echo $val->id; ?>&status=0&act=status_update"
                                                   class="confirmation">Activate</a>
                                                <?php
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="?page=xml-feeder&val=<?php echo $val->id; ?>&act=upd">Update</a> |
                                            <a href="?page=xml-feeder&val=<?php echo $val->id; ?>&act=del"
                                               class="confirmation">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <script type="text/javascript">
                            var elems = document.getElementsByClassName('confirmation');
                            var confirmIt = function (e) {
                                if (!confirm('Are you sure?')) e.preventDefault();
                            };
                            for (var i = 0, l = elems.length; i < l; i++) {
                                elems[i].addEventListener('click', confirmIt, false);
                            }
                        </script>
    <?php
}

add_action('admin_menu', 'xmlFeeder_menu');


?>
