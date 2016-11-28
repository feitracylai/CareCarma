<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\user\models\Profile;
?>
<tr>
    <td align="center" valign="top" class="fix-box">
        <!-- start  container width 600px -->
        <table width="600" align="center" border="0" cellspacing="0"
            cellpadding="0" class="container"
            style="background-color: #ffffff;">


            <tr>
                <td valign="top">
                    <!-- start container width 560px -->
                    <table width="540" align="center" border="0"
                        cellspacing="0" cellpadding="0"
                        class="full-width" bgcolor="#ffffff"
                        style="background-color: #ffffff;">


                        <!-- start text content -->
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0"
                                    cellspacing="0" cellpadding="0"
                                    align="center">
                                    <tr>
                                        <td valign="top" width="auto"
                                            align="center">
                                            <!-- start button -->
                                            <table border="0"
                                                align="center"
                                                cellpadding="0"
                                                cellspacing="0">
                                                <tr>
                                                    <td width="auto"
                                                        align="center"
                                                        valign="middle"
                                                        height="28"
                                                        style="background-color: #ffffff; background-clip: padding-box; font-size: 26px; font-family: Open Sans, Arial, Tahoma, Helvetica, sans-serif; text-align: center; color: #a3a2a2; font-weight: 300; padding-left: 18px; padding-right: 18px;">

                                                        <span
                                                        style="color: #555555; font-weight: 300;">
                                                            <?php echo Yii::t('UserModule.views_mails_UserInviteSelf', 'Welcome to %appName%', array('%appName%' => '<strong>' . Html::encode(Yii::$app->name) . '</strong>')); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table> <!-- end button -->
                                        </td>
                                    </tr>


                                </table>
                            </td>
                        </tr>
                        <!-- end text content -->


                    </table> <!-- end  container width 560px -->
                </td>
            </tr>
        </table> <!-- end  container width 600px -->
    </td>
</tr>


<tr>
    <td align="center" valign="top" class="fix-box">
        <!-- start  container width 600px -->
        <table width="600" align="center" border="0" cellspacing="0"
            cellpadding="0" class="container"
            style="background-color: #ffffff;">


            <tr>
                <td valign="top">
                    <!-- start container width 560px -->
                    <table width="540" align="center" border="0"
                        cellspacing="0" cellpadding="0"
                        class="full-width" bgcolor="#ffffff"
                        style="background-color: #ffffff;">


                        <!-- start text content -->
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0"
                                    cellspacing="0" cellpadding="0"
                                    align="center">


                                    <!-- start text content -->
                                    <tr>
                                        <td valign="top">
                                            <table border="0"
                                                cellspacing="0"
                                                cellpadding="0"
                                                align="center">


                                                <!--start space height -->
                                                <tr>
                                                    <td height="15"></td>
                                                </tr>
                                                <!--end space height -->

                                                <tr>
                                                    <td
                                                        style="font-size: 14px; line-height: 22px; font-family: Open Sans, Arial, Tahoma, Helvetica, sans-serif; color: #777777; font-weight: 300; text-align: center;">
                                                    	
                                                    	<?php 
                                                    	// check if a proper user is logged in or the new user was created by the json api
                                                    	if (isset(Yii::$app->user) && isset(Yii::$app->user->identity)) {
                                                            echo Yii::t('MassuserimportModule.base', '%creator% has created an user for you.', [
                                                                '%creator%' => Html::encode(Yii::$app->user->identity->username)
                                                            ]);
                                                        } else {
                                                            echo Yii::t('MassuserimportModule.base', 'A user was created for you.');
                                                        } 
                                                        echo Yii::t('MassuserimportModule.base', 'Welcome to %appName%! Please click on the button below to login with your new username and password.', ['%appName%' => Html::encode(Yii::$app->name)]);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 14px; line-height: 22px; font-family: Open Sans, Arial, Tahoma, Helvetica, sans-serif; color: #777777; font-weight: 300; text-align: center;">

                                                    
                                                    	<?php echo Yii::t('MassuserimportModule.base', 'Your username: %userName%.', array('%userName%' => Html::encode($model->user->username))); ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 14px; line-height: 22px; font-family: Open Sans, Arial, Tahoma, Helvetica, sans-serif; color: #777777; font-weight: 300; text-align: center;">

                                                    	<?php echo Yii::t('MassuserimportModule.base', 'Your password: %password%.', array('%password%' => Html::encode($model->password->newPassword))); ?>

                                                    </td>
                                                </tr>

                                                <!--start space height -->
                                                <tr>
                                                    <td height="15"></td>
                                                </tr>
                                                <!--end space height -->


                                            </table>
                                        </td>
                                    </tr>
                                    <!-- end text content -->

                                    <tr>
                                        <td valign="top" width="auto"
                                            align="center">
                                            <!-- start button -->
                                            <table border="0"
                                                align="center"
                                                cellpadding="0"
                                                cellspacing="0">
                                                <tr>
                                                    <td width="auto"
                                                        align="center"
                                                        valign="middle"
                                                        height="32"
                                                        style="background-color: #7191a8; border-radius: 5px; background-clip: padding-box; font-size: 14px; font-family: Open Sans, Arial, Tahoma, Helvetica, sans-serif; text-align: center; color: #ffffff; font-weight: 600; padding-left: 30px; padding-right: 30px; padding-top: 5px; padding-bottom: 5px;">

                                                        <span
                                                        style="color: #ffffff; font-weight: 300;">
                                                            <a
                                                            href="<?php echo Url::toRoute(["/user/account/change-password"], true); ?>"
                                                            style="text-decoration: none; color: #ffffff; font-weight: 300;">
                                                                <strong><?php echo Yii::t('MassuserimportModule.base', 'Log in'); ?></strong>
                                                        </a>
                                                    </span>
                                                    </td>

                                                </tr>
                                            </table> <!-- end button -->
                                        </td>

                                    </tr>

                                </table>
                            </td>
                        </tr>
                        <!-- end text content -->

                        <!--start space height -->
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <!--end space height -->


                    </table> <!-- end  container width 560px -->
                </td>
            </tr>
        </table> <!-- end  container width 600px -->
    </td>
</tr>