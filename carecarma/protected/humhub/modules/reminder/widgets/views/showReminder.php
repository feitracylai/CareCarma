<?php

use yii\helpers\Url;
?>
<script type="text/javascript">
//    $(document).ready(showReminder);

//    ReloadInterval = 15000;
//    setInterval(showReminder, ReloadInterval);


    function showReminder() {
        $('#globalModal').modal({
            remote: '<?php echo Url::to(['/reminder/show']); ?>',
            show: true
        })
    }

</script>