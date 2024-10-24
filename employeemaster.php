<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border: 2px solid black;
        }
        tr, td {
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <center>
        <!-- <div id="selection-count">
            <p>Selected count: <span id="count">0</span></p>
        </div> -->
        <h2>Category Page</h2>
        <form action="" method="POST" id="mypage">
            <table>
                <tr>
                    <td>
                       Employee Name: <input type="text" name="name" id="name">
                    </td>
                </tr>
                <tr>
                    <td>
                    <!-- Mobile No. Length: <input type="text" name="mobilenolength" id="mobilenolength"> -->
                     Country <select name="countryid" id="countryid">
                        <option value=""></option>

                        <input type="text" name="mobileno" id="mobileno">
                     </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="submit" name="submit" id="submit">
                        <input type="reset" value="Reset">
                        <input type="hidden" name="id" id="id">
                        <input type="submit" value="update" id="updateID" name="updateID">
                    </td>
                </tr>
            </table>
        </form>
    </center>
    <center>
        <h2>Category Fetch:</h2>
        <table>
            <thead>
                <tr>
                    <!-- <td>All</td> -->
                    <!-- <td>ID:</td> -->
                    <td>Country:</td>
                    <td>Mobile No. Length:</td>
                    <td colspan="2">Operation</td>
                </tr>
            </thead>
            <tbody id="tabledata">
            </tbody>
        </table>
     
    </center>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
        });

        $('#tabledata').on('click', '.DeleteRecoard', function() {
            var id = $(this).attr('data-id');
            deleted(id);
        });

        $('#tabledata').on('click', '.EditRecoard', function() {
            var id = $(this).attr('data-id');
            edited(id);
        });
        subcategory()
        function subcategory()
        {
            $.ajax({
                method:'POST',
                url:'master.php',
                dataType:'json',
                data:{action:'fillcountry'},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        // table += '<option value = "">Select Country</option>'
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value="' + resultdata.categorydata[i].id + '" data-mobilenolength="' + resultdata.categorydata[i].mobilenolength + '">' + resultdata.categorydata[i].country + '</option>';

                            
                        }
                        $('#countryid').html(table);
                    }
                }
            });
        }

    //     $('#countryid').on('change', function() {
    //     var mobilenolength = $(this).find(':selected').data('mobilenolength');

    //     // For debugging purposes, log the mobilenolength
    //     console.log("Selected Country Mobile No. Length: ", mobilenolength);

    //     if (mobilenolength) {
    //         $('#mobileno').off('input').on('input', function() {
    //             var mobileValue = $(this).val();

    //             // If the user enters more digits than allowed, trim the input
    //             if (mobileValue.length > mobilenolength) {
    //                 alert('Mobile number cannot exceed ' + mobilenolength + ' digits.');
    //                 // $(this).val(mobileValue.slice(0, mobilenolength));  // Trim extra digits
    //             }
    //         });
    //     }
    // });

   
    // function mobilenolengthcheck()
    // {
    //     $('#countryid').on('change',function(){
    //     var mobileno = $(this).find(':selected').data('mobilenolength');
    //     if(mobileno)
    //     {
    //         $('#mobileno').input(function(){
    //             var mobileValue = $(this).val();
    //             if(mobileValue.length >= mobileno)
    //             {
    //                 alert('Mobile number cannot exceed ' + mobileno + 'digits.');
    //             }
    //         })
    //     }
    // })
    // }
        $('#mypage').validate({
            rules: {
                country: {
                    required: true
                },
                mobilenolength:{
                    required: true,
                }
            },
            messages: {
                country: {
                    required: "country is required"
                },
                mobilenolength:{
                    required: "mobile number is required",
                }
            },
            submitHandler: function(form) {
                    var mobilenolength = $('#countryid').find(':selected').data('mobilenolength');
                    var mobileno = $('#mobileno').val();

                    // Check if mobile number matches the required length
                    if (mobileno.length != mobilenolength) {
                        alert("Mobile number must be exactly " + mobilenolength + " digits long.");
                        return false;
                    }

                var formdata = new FormData(form);
                $.ajax({
                    method: "POST",
                    url: 'country_insert.php',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(data) {
                        var jsonData = JSON.stringify(data);
                        var resultdata = jQuery.parseJSON(jsonData);

                        if (resultdata.status == 1) {
                            alert(resultdata.message);
                        } else {
                            alert(resultdata.message);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
