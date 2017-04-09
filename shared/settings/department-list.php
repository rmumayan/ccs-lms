<div id="department-list">
    <a href="department.php?id=0&camp=<?php echo $campus_id ?>" class="btn btn-primary pull-right btn-sm">Add</a>
    <h3>Department List
    </h3>

    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Department</th>
                <th>Dean</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($dept_list as $dept) {
                    echo '<tr class="tr-as-link" data-id="'.$dept['id'].'" page="department">
                            <td class="">'.$dept['name'].'</td>
                            <td class="">'.$dept['full_name'].'</td>
                            <td class="">'.$dept['username'].'</td>
                            </tr>';
                }
            ?>
        </tbody>
    </table>
    <br>
</div>

<br><br><br><br><br><br>