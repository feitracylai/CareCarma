<?php


$userId = Yii::$app->user->id;
$user = \humhub\modules\user\models\User::findOne(['id' => $userId]);

?>

<link href="<?php if ($user != null) {echo $this->theme->getBaseUrl() . '/css/'.$user->theme;} else {echo $this->theme->getBaseUrl() . '/css/theme.css';} ?>" rel="stylesheet">
<link href="<?php echo $this->theme->getBaseUrl() . '/font/open_sans/open-sans.css'; ?>" rel="stylesheet">


