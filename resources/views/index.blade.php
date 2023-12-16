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
    <div class=" m-0 w-100 row p-4">
        <div class="table-responsive col-8">
            <div class="d-flex justify-content-between align-items-center p-2">
                <h4 class="">All Tasks</h4>
                <div class="d-flex justify-content-between align-items-center">
                    <select id="filter-status" class="form-control mx-2" onchange="fetchAllTasks();">
                        <option value="" selected>All Task</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <input type="date" id="filter-date" class="form-control mx-2" onchange="fetchAllTasks();">
                </div>
            </div>
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
            <div id="loading" class="d-flex justify-content-center align-items-center flex-column">
                <img src="https://media.tenor.com/JBgYqrobdxsAAAAi/loading.gif" width="50px" height="50px" alt="">
                <p>Loading...!!!</p>
            </div>
        </div>
        <div class="col-4">
            <form id="create-task-form">
                <h4>Create New Task</h4>
                <div class="form-group my-2">
                    <label>Title</label>
                    <input type="text" maxlength="100" required id="title" placeholder="Enter title" class="form-control">
                </div>
                <div class="form-group my-2">
                    <label>Description</label>
                    <input type="text" required id="description" placeholder="Enter description" class="form-control">
                </div>
                <div class="form-group my-2">
                    <button type="submit" id="create-btn" class="btn btn-primary">Create Task</button>
                </div>
            </form>
            <form id="update-task-form" class="d-none">
                <input type="hidden" id="update-id">
                <h4>Update Task</h4>
                <div class="form-group my-2">
                    <label>Title</label>
                    <input type="text" required id="update-title" placeholder="Enter title" class="form-control">
                </div>
                <div class="form-group my-2">
                    <label>Description</label>
                    <input type="text" required id="update-description" placeholder="Enter description" class="form-control">
                </div>
                <div class="form-group my-2">
                    <label>Status</label>
                    <select id="update-status" required class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="form-group my-2">
                    <button type="submit" id="update-btn" class="btn btn-primary">Update Task</button>
                    <button type="button" onclick="fetchAllTasks();" class="btn btn-danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <div class="container-fluid bg-light fixed-bottom">
            <div class="row">
                <div class="col-12 text-center py-2">
                    <p class="m-0">Developed by <a href="https://imronit2001.github.io/ronit/" target="_blank">Ronit Singh</a></p>
                </div>
            </div>
        </div>
    </footer>

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
            $('#loading').html(`<img src="https://media.tenor.com/JBgYqrobdxsAAAAi/loading.gif" width="50px" height="50px" alt=""><p>Loading...!!!</p>`);
            $('#taskBody').html(``);
            $("#create-task-form").trigger("reset");
            $("#update-task-form").trigger("reset");
            $('#create-task-form').removeClass('d-none');
            $('#update-task-form').addClass('d-none');
            let filterStatus = $('#filter-status').val();
            let filterDate = $('#filter-date').val();
            // Get all tasks
            $.ajax({
                url: '/api/list-all-task?status=' + filterStatus + '&date=' + filterDate,
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
                                <td class="${task.status=='Pending'?'text-danger':'text-success'}">${task.status}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="updateTask('${task.id}','${task.title}','${task.description}','${task.status}')">Update</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTask('${task.id}');">Delete</button>
                                </td>
                            </tr>`;
                    });
                    $('#taskBody').html(html);
                    if(response.data.length==0)
                        $('#loading').html(`<p>No Task Found</p>`);
                    else
                        $('#loading').html(``);
                }
            });
        }

        function updateTask(id, title, description, status) {
            $('#create-task-form').addClass('d-none');
            $('#update-task-form').removeClass('d-none');
            $('#update-title').val(title);
            $('#update-description').val(description);
            $('#update-status').val(status);
            $('#update-id').val(id);
        }

        // Create task
        $('#create-task-form').on('submit', function(e) {
            e.preventDefault();
            // disable button
            $('#create-btn').attr('disabled', true);
            // set button text to loading...
            $('#create-btn').html('Creating...');
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
                    // enable button
                    $('#create-btn').attr('disabled', false);
                    // set button text to Create Task
                    $('#create-btn').html('Create Task');
                }
            });
        });

        // Update task
        $('#update-task-form').on('submit', function(e) {
            e.preventDefault();
            // disable button
            $('#update-btn').attr('disabled', true);
            // set button text to loading...
            $('#update-btn').html('Updating...');
            let id = $('#update-id').val();
            let title = $('#update-title').val();
            let description = $('#update-description').val();
            let status = $('#update-status').val();
            $.ajax({
                url: '/api/update-task',
                type: 'POST',
                data: {
                    id: id,
                    title: title,
                    description: description,
                    status: status
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    fetchAllTasks();
                    // enable button
                    $('#update-btn').attr('disabled', false);
                    // set button text to Update Task
                    $('#update-btn').html('Update Task');
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
