<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Home</h1>

    <?php
        use Mub\PollsWithPhp\model\Poll;

        $polls = Poll::getPolls();
        var_dump($polls);
        foreach ($polls as $poll) {
            echo "<div>".$poll->getTitle()."</div>";
        }
    ?>
</body>
</html>