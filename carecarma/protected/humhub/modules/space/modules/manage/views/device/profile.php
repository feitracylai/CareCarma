
<?php
/**
 * User: wufei
 * Date: 4/6/2016
 * Time: 5:16 PM
 */


use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use yii\helpers\Url;

$this->registerJs("var receiverImageUploaderUserGuid='" . $user->guid . "';", \yii\web\View::POS_BEGIN);
$this->registerJs("var receiverImageUploaderCurrentUserGuid='" . Yii::$app->request->get('rguid') . "';", \yii\web\View::POS_BEGIN);
$this->registerJs("var receiverImageUploaderUrl='" . Url::to(['/user/account/profile-image-upload', 'userGuid' => $user->guid]) . "';", \yii\web\View::POS_BEGIN);
?>


<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_account_edit', "<strong>{first} {last}</strong> Details", array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>

        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>
    </div>
    <div class="panel-body">
        <div class="col-md-2">
            <div class="image-upload-container profile-user-photo-container" style="width: 140px; height: 140px;" id="receiver-photo-container">

                <?php
                /* Get original profile image URL */

                $profileImageExt = pathinfo($user->getProfileImage()->getUrl(), PATHINFO_EXTENSION);

                $profileImageOrig = preg_replace('/.[^.]*$/', '', $user->getProfileImage()->getUrl());
                $defaultImage = (basename($user->getProfileImage()->getUrl()) == 'default_user.jpg' || basename($user->getProfileImage()->getUrl()) == 'default_user.jpg?cacheId=0') ? true : false;
                $profileImageOrig = $profileImageOrig . '_org.' . $profileImageExt;

                if (!$defaultImage) {
                    ?>

                    <!-- profile image output-->
                    <a data-toggle="lightbox" data-gallery="" href="<?php echo $profileImageOrig; ?>#.jpeg"
                       data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.widgets_views_showFiles', 'Close'); ?></button>'>
                        <img class="img-rounded profile-user-photo" id="user-profile-image"
                             src="<?php echo $user->getProfileImage()->getUrl(); ?>"
                             data-src="holder.js/140x140" alt="140x14 0" style="width: 140px; height: 140px;"/>
                    </a>

                <?php } else { ?>

                    <img class="img-rounded profile-user-photo" id="user-profile-image"
                         src="<?php echo $user->getProfileImage()->getUrl(); ?>"
                         data-src="holder.js/140x140" alt="140x140" style="width: 140px; height: 140px;"/>

                <?php } ?>

                <!-- check if the current user is the profile owner and can change the images -->
                <form class="fileupload" id="receiverfileupload" action="" method="POST" enctype="multipart/form-data"
                      style="position: absolute; top: 0; left: 0; opacity: 0; height: 140px; width: 140px;">
                    <input type="file" name="profilefiles[]">
                </form>

                <div class="image-upload-loader" id="receiver-profile-image-upload-loader" style="padding-top: 60px;">
                    <div class="progress image-upload-progess-bar" id="receiver-profile-image-upload-bar">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="00"
                             aria-valuemin="0"
                             aria-valuemax="100" style="width: 0%;">
                        </div>
                    </div>
                </div>

                <div class="image-upload-buttons" id="receiver-profile-image-upload-buttons">
                    <a href="#" onclick="javascript:$('#receiverfileupload input').click();" class="btn btn-info btn-sm tt"
                       data-toggle="tooltip" data-placement="bottom" title=""
                       data-original-title="<?php echo Yii::t('UserModule.widgets_views_uploadImage', 'Upload image'); ?>"><i
                            class="fa fa-cloud-upload"></i></a>
                    <a id="receiver-profile-image-upload-edit-button"
                       style="<?php
                       if (!$user->getProfileImage()->hasImage()) {
                           echo 'display: none;';
                       }
                       ?>"
                       href="<?php echo Url::to(['/user/account/crop-profile-image', 'userGuid' => $user->guid, 'spaceGuid' => $space->guid]); ?>"
                       class="btn btn-info btn-sm tt" data-target="#globalModal" data-toggle="tooltip" data-placement="bottom" title=""
                       data-original-title="<?php echo Yii::t('UserModule.widgets_views_editImage', 'Edit image'); ?>"><i
                            class="fa fa-edit"></i></a>
                    <?php
                    echo \humhub\widgets\ModalConfirm::widget(array(
                        'uniqueID' => 'modal_receiver_profileimagedelete',
                        'linkOutput' => 'a',
                        'title' => Yii::t('UserModule.widgets_views_deleteImage', '<strong>Confirm</strong> image deleting'),
                        'message' => Yii::t('UserModule.widgets_views_deleteImage', 'Do you really want to delete your profile image?'),
                        'buttonTrue' => Yii::t('UserModule.widgets_views_deleteImage', 'Delete'),
                        'buttonFalse' => Yii::t('UserModule.widgets_views_deleteImage', 'Cancel'),
                        'linkContent' => '<i class="fa fa-times"></i>',
                        'cssClass' => 'btn btn-danger btn-sm tt',
                        'style' => $user->getProfileImage()->hasImage() ? 'data-toggle="tooltip" data-placement="bottom" title=""
                       data-original-title="Delete image"' : 'display: none;',
                        'linkHref' => Url::to(["/user/account/delete-profile-image", 'type' => 'receiver-profile', 'userGuid' => $user->guid]),
                        'confirmJS' => 'function(jsonResp) { resetProfileImage(jsonResp); }'
                    ));
                    ?>
                </div>

            </div>
        </div>

        <div class="col-md-10">
            <div id="profile-form-container" style="display: none;">
                <?php $form = \yii\widgets\ActiveForm::begin(['enableClientValidation' => false]); ?>
                <?php echo $hForm->render($form); ?>
                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
            <div id="profile-loader" class="loader">
                <div class="sk-spinner sk-spinner-three-bounce">
                    <div class="sk-bounce1"></div>
                    <div class="sk-bounce2"></div>
                    <div class="sk-bounce3"></div>
                </div>
            </div>
        </div>





    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {

        // save the tab to show
        var activeTab = 0;

        // add tab content <div>
        $('#profile-form-container form').prepend('<div class="tab-content"></div>');

        // add clickable tabs
        $('#profile-form-container form').prepend('<ul id="profile-tabs" class="nav nav-tabs" data-tabs="tabs"></ul>');

        // go through all fieldsets with inputs (categories)
        $('#profile-form-container form fieldset legend').each(function (index, value) {

            // save current tab index by the first error to activate him later
            if (checkErrors($(this)) == true && activeTab == 0) {
                activeTab = index;
            }

            // save category text
            var _category = $(this).text();

            // build tab structure
            var _tab = '<li><a href="#category-' + index + '" data-toggle="tab">' + _category + '</a></li>';

            // add tab structure to tab
            $('#profile-tabs').append(_tab);

            // build tab content container
            var _tabContent = '<div class="tab-pane" id="category-' + index + '"></div>';

            // add content to tab content container
            $('.tab-content').append(_tabContent);

            // clone every inputs from original form
            var $inputs = $(this).parent().children(".form-group").clone();

            // add cloned inputs to current tab content container
            $('#category-' + index).html($inputs);

            // remove original inputs from original form
            $(this).parent().remove();

        })

        // add an <hr> between tab and submit button
        $('#profile-form-container form .form-group-buttons').before('<hr>');


        // check if errorSummary element exists
        if ($('.errorSummary').length != null) {

            // clone element
            var _errorSummary = $('.errorSummary').clone();

            // remove original element
            $('.errorSummary').remove();

            // add cloned element at the top
            $('#profile-form-container form').prepend(_errorSummary);

        }

        // activate the first tab or the tab with the first error
        $('#profile-tabs a[href="#category-' + activeTab + '"]').tab('show')

        // hide loader
        $('#profile-loader').hide();

        // show created tab element
        $('#profile-form-container').show();


    })


    /**
     * Check for errors in a specific category
     * @param _object
     * @returns {boolean}
     */
    function checkErrors(_object) {

        // save standard result
        var _error = false;

        // go through every input
        _object.parent().children(".form-group").each(function (index, value) {

            // if an input have the class "error"
            if ($(this).children('.form-control').hasClass("error")) {

                // change standard result
                _error = true;

                // stop loop/function
                return false;
            }
        })

        // return result
        return _error;

    }

</script>
