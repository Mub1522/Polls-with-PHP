<?php

use Mub\PollsWithPhp\model\Poll;

if (isset($_POST['title'])) {
    if (isset($_POST['options'])) {
        $title = $_POST['title'];
        $options = $_POST['options'];

        $poll = new Poll($title, true);

        $poll->save();
        $poll->insertOptions($options);

        header("Location: http://localhost/Polls-with-PHP/?view=home");
    }
} else {
    header("Location: http://localhost/Polls-with-PHP/?view=home");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Options</title>
</head>

<body>
    <form action="?view=options" method="POST">
        <input type="hidden" name="title" value="<?= $_POST['title'] ?>">
        <input type="text" name="options[]" id="">
        <input type="text" name="options[]" id="">

        <div>

        </div>

        <button type="submit" value="createPoll">Add options</button>
    </form>
</body>

</html>