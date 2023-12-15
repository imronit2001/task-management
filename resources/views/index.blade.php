<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand px-4" href="#">Task Management</a>
        </div>
    </nav>
    <div class=" m-0 w-100 row">
        <div class="table-responsive col-7">
            <table class="table table-hover">
                <thead>
                    <th>#</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody id="taskBody">

                </tbody>
            </table>
        </div>
        <div class="col-4">
            <form id="create-task-form">
                <div class="form-group my-2">
                    <label>Title</label>
                    <input type="text" id="title" placeholder="Enter title" class="form-control">
                </div>
                <div class="form-group my-2">
                    <label>Description</label>
                    <input type="text" id="description" placeholder="Enter description" class="form-control">
                </div>
                <div class="form-group my-2">
                    <button type="submit" id="create-btn" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatDate(originalTimestamp) {
            const date = new Date(originalTimestamp);
            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            const formattedDate = date.toLocaleDateString('en-US', options);
            return formattedDate;
        }
        // Delete task
        async function deleteTask(id) {
            $.ajax({
                url: '/api/delete-task?id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    alert(response.message);
                    fetchAllTasks();
                }
            });
        }

        fetchAllTasks();

        async function fetchAllTasks() {
            // Get all tasks
            $.ajax({
                url: '/api/list-all-task',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    let html = ``;
                    $.each(response.data, function(key, task) {
                        html += `<tr>
                                <td>${key + 1}</td>
                                <td>${formatDate(task.created_at)}</td>
                                <td>${task.title}</td>
                                <td>${task.description}</td>
                                <td>${task.status}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="updateTask('${task.id}','${task.title}','${task.description}','${task.status}')">Update</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTask('${task.id}');">Delete</button>
                                </td>
                            </tr>`;
                    });
                    $('#taskBody').html(html);
                }
            });
        }

        // Create task
        $('#create-task-form').on('submit', function(e) {
            e.preventDefault();
            let title = $('#title').val();
            let description = $('#description').val();
            $.ajax({
                url: '/api/create-new-task',
                type: 'POST',
                data: {
                    title: title,
                    description: description,
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    fetchAllTasks();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
</body>

</html>
