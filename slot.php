<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Master</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap Timepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            text-align: center;
        }
        .removeRow {
            color: red;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Slot Master</h2>
        <hr>

        <form action="" method="POST" id="slotmasterForm">
            <div class="form-group">
                <label for="startdate">Start Date</label>
                <input type="text" class="form-control datepicker" name="startdate" id="startdate" placeholder="Select Start Date">
            </div>
            <div class="form-group">
                <label for="enddate">End Date</label>
                <input type="text" class="form-control datepicker" name="enddate" id="enddate" placeholder="Select End Date">
            </div>

            <h2>Details:</h2>
            <div class="form-group">
                <label for="starttime">Start Time</label>
                <div class="input-group bootstrap-timepicker">
                    <input type="text" class="form-control timepicker" name="starttime" id="starttime" placeholder="Select Start Time">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-clock"></i></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="endtime">End Time</label>
                <div class="input-group bootstrap-timepicker">
                    <input type="text" class="form-control timepicker" name="endtime" id="endtime" placeholder="Select End Time">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-clock"></i></span>
                    </div>
                </div>
            </div>

            <button type="button" id="addData" class="btn btn-primary">Add</button>

            <h2 class="mt-4">Grid Record</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="gridBody">
                </tbody>
            </table>

            <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-success">
            <input type="hidden" name="purchaseid" id="purchaseid">
            <input type="submit" value="Update" id="updateId" name="updateId" class="btn btn-warning">
            <input type="hidden" name="action" id="action">
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Timepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<!-- Tempus Dominus JS -->



    <script>
    $(document).ready(function() {
        // Initialize datepicker for start and end date
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        

        $('#starttime').timepicker({
        showMeridian: true, // Enables AM/PM format
        defaultTime: false, // No default time
        minuteStep: 5 // Step for minutes
    }).on('click', function() {
        $(this).timepicker('showWidget'); // Show the time picker widget on click
    });

    $('#endtime').timepicker({
        showMeridian: true, // Enables AM/PM format
        defaultTime: false, // No default time
        minuteStep: 5 // Step for minutes
    }).on('click', function() {
        $(this).timepicker('showWidget');
    });


    $('#addData').click(function() 
    {
        var starttime = $('#starttime').val();
        var endtime = $('#endtime').val();
        var isConflict = false;

        var newStartTime = new Date('1970/01/01 ' + starttime);
        var newEndTime = new Date('1970/01/01 ' + endtime);

        // if (newEndTime <= newStartTime) {
        //     alert('End time must be greater than start time.');
        //     return;
        // }

        $('#gridBody tr').each(function() 
        {
            var existingStarttime = $(this).find('.tblstarttime').val();
            var existingEndtime = $(this).find('.tblendtime').val();

            var existingStartTimeObj = new Date('1970/01/01 ' + existingStarttime);
            var existingEndTimeObj = new Date('1970/01/01 ' + existingEndtime);

            if (newStartTime < existingEndTimeObj && newEndTime > existingStartTimeObj) 
            {
                isConflict = true;
                return false; 
            }
        });

        if (isConflict) 
        {
            alert('This time range (' + starttime + ' to ' + endtime + ') overlaps with an existing time range.');
        } 
        else 
        {
            if(starttime && endtime)
            {
                var table = '';
                table += '<tr>';
                table += '<td><input type="hidden" class="tblstarttime" id="tblstarttime" name="tblstarttime[]" value="' + starttime + '">'+starttime+'</td>';
                table += '<td><input type="hidden" name="tblendtime[]" class="tblendtime" id="tblendtime" value="' + endtime + '">'+endtime+'</td>';
                table += '<td><div class="form-check form-switch form-check-reverse">';
                table += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckReverse">';
                table += '</div>';
                table += '</td>';
                table += '<td><span class="removeRow">❎</span></td>';
                table += '</tr>';

                $('#gridBody').append(table);
                $('#starttime').val('');
                $('#endtime').val('');
            }
            else
            {
                alert("This Field is required")
            }
            
        }
    });

            $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });
    });

        $('#slotmasterForm').validate({
            rules:{
                startdate:{
                    required : true
                },
                enddate:{
                    required:true,
                }
            },
            messages:{
                startdate:{
                    required:"Start Date is required!"
                },
                enddate:{
                    required:'End Date is required!',
                }
            },

            submitHandler:function(form) 
            {
                
                // if ($('#purchaseid').val() == '') 
                // {
                //     var url = 'slotinsert.php';
                // }
                // else
                // {
                //     var url = 'purchase_update2.php';
                // }
                var formdata = new FormData(form)
                $.ajax({
                    method:'POST',
                    url:'slotinsert.php',
                    dataType:'json',
                    data:formdata,
                    processData:false,
                    contentType:false,
                    success:function(data)
                    {
                        var jsonData = JSON.stringify(data);
                        var resultdata = jQuery.parseJSON(jsonData);

                        if(resultdata.status == 1)
                        {
                            alert(resultdata.message);
                            $('#gridBody').empty();
                            // purchase_master_fetch()
                            // showsubmitbutton()
                        }
                        else
                        {
                            alert(resultdata.message);
                        }
                    }
                })
            }
            
        })
    </script>
</body>

</html>
