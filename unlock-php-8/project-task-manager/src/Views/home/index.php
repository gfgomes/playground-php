<h1>Task Manager</h1>
<form action="" method="POST">
    <input type="text" name="task" placeholder="Add a new task" required>
    <button type="submit">Add Task</button>
</form>
<?php if (!empty($tasks)): ?>
    <h2>New Task Added:</h2>
    <?php foreach ($tasks as $index => $task): ?>
        <li style="margin-bottom: 5px">
            <?= $task['task']; ?>
            <?php if (!$task['completed']): ?>
                <form action="" method="POST" style="display: inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <button type="submit" name="completed">Mark completed</button>
                </form>
            <?php else: ?>
                <strong style="color:green">concluded</strong>
            <?php endif; ?>
            <form action="" method="POST" style="display: inline;">
                <input type="hidden" name="index" value="<?php echo $index; ?>">
                <button type="submit" name="delete">delete</button>
            </form>
        </li>
    <?php endforeach; ?>
    <form action="" method="POST" style="margin-top: 25px;">
        <button type="submit" name="clear">Clear All</button>
    </form>
<?php endif; ?>