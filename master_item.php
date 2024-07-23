<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table{
        border:2px solid black;
    }
    tr,td{
        border:2px solid black;
    }
    label.error{
        color:#f00;
    }
</style>
<body>
    <center> 
         <div>
            <p>Total Count <span id="count">0</span></p>
        </div>
        <h2>Item_Master:</h2>
        <form action="" method="POST" id="mypage">
            <table>
                <tr>
                    <td>
                       Category: <select type="text" name="catid" id="catid">
                        <option value="" selected>Select Category</option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td>
                       Subcategory: <select type="text" name="subcatid" id="subcatid">
                       <option value="" selected>Select SubCategory</option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td>
                       ItemName: <input type="text" name="itemname" id="itemname">
                    </td>
                </tr>
                <tr>
                    <td>
                        ItemNo:<input type="text" name="itemno" id="itemno">
                    </td>
                </tr>
                <tr>
                    <td>
                       ItemAmount: <input type="text" name="itemamount" id="itemamount">
                    </td>
                </tr>
                <tr>
                    <td>
                       Genarate No: <input type="text" name="no" id="no">
                       
                       
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="button" onclick="generateNumber()">Generate</button>
                        <input type="submit" name="submit" id="submit" value="Submit">
                        <input type="reset" value="Reset">
                        <input type="hidden" name="id" id="id">
                        <input type="submit" value="Update" name="updateId" id="updateId">
                    </td>
                </tr>
            </table>
        </form>
    </center>
    <center>
        <h2>Item Fetch:</h2>
        <table>
            <thead>
                <tr>
                    <td>ID:</td>
                    <td>Category</td>
                    <td>SubCategory</td>
                    <td>Item Name</td>
                    <td>Item No</td>
                    <td>Item Amount</td>
                    <td colspan="2">Operations</td>
                </tr>
            </thead>
            <tbody id="tablebody">

            </tbody>
        </table>
    </center>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>
    <script>
        $(document).ready(function(){
            category()
            subcategory()
            itemfetch()
            showformsubmit() 
        });

        // function generateNumber() {
        //     const itemName = document.getElementById('itemname').value;
        //     if (itemName.length < 3) {
        //         alert('ItemName must be at least 3 characters long.');
        //         return;
        //     }

        //     const prefix = itemName.substring(0, 3).toUpperCase();
        //     const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        //     let randomString = '';
            
        //     for (let i = 0; i < 5; i++) {
        //         randomString += characters.charAt(Math.floor(Math.random() * characters.length));
        //     }

        //     const generatedNumber = prefix + randomString;
        //     document.getElementById('no').value = generatedNumber;
        // } 

        $('#tablebody').on('click','.count',function(){
            var count = $('.count:checked').length;
            $('#count').text(count)
        })

            function generateRandomString(length) 
            {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = '';
                const charactersLength = characters.length;
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }
                return result;
            }

        

            function geneno()
            {
                var itemname = $('#itemname').val();
                if(itemname.length >=3)
                {
                    var number = itemname.substring(0,3);
                    var randomnumber = generateRandomString(5);
                    var generateno = number + randomnumber
                    $('#no').val(generateno);
                }
                else
                {
                    $('#no').val('');
                }
            }
            $('#itemname').on('input', geneno);
        
        $('#catid').change(function(){
            subcategory()
        })

        $('#tablebody').on('click','.DeleteRecoard',function(){
            var id = $(this).attr('data-id');
            itemdelete(id)
        });

        $('#tablebody').on('click','.EditRecoard',function(){
            var id = $(this).attr('data-id');
            itemedit(id)
        });

        function showformsubmit() 
        {
            $('#submit').show();
            $('#updateId').hide();
            $('#id').val('');
            $('#catid').val('');
            $('#subcatid').val('');
            $('#itemname').val('');
            $('#itemno').val('');
            $('#itemamount').val('');
        }
        function showformupdate()
        {
            $('#submit').hide();
            $('#updateId').show();
        }

        function category()
        {
            $.ajax({
                method:'POST',
                url:'category_fetch.php',
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<option value = "">Select SubCategory</option>'
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value = "'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].name+'</option>'
                        }
                        $('#catid').html(table);
                        subcategory()

                    }
                }
            })
        }

        function subcategory()
        {
            var catid = $('#catid').val();
            $.ajax({
                method:'POST',
                url:'SubCategory_Fetch.php',
                dataType:'json',
                data:{catid:catid,flag:1},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        table += '<option value = "">Select SubCategory</option>'
                        for(var i in resultdata.categorydata)
                        {
                            table += '<option value = "'+resultdata.categorydata[i].id+'">'+resultdata.categorydata[i].subcatname+'</option>'
                            
                        }
                        $('#subcatid').html(table);
                    }
                }
            });
        }

       
        function itemfetch()
        {
            $.ajax({
                method:'POST',
                url:'item_Fetch.php',
                dataType:'json',
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var table = '';
                        for(var i in resultdata.categorydata)
                        {
                            table += '<tr>';
                            table += '<td>'+resultdata.categorydata[i].id+'</td>';
                            table += '<td>'+resultdata.categorydata[i].catid+'</td>';
                            table += '<td>'+resultdata.categorydata[i].subcatid+'</td>';
                            table += '<td>'+resultdata.categorydata[i].itemname+'</td>';
                            table += '<td>'+resultdata.categorydata[i].itemno+'</td>';
                            table += '<td>'+resultdata.categorydata[i].itemamount+'</td>';
                            table += '<td><a href="javascript:void(0)" class="EditRecoard" data-id="'+resultdata.categorydata[i].id+'">Edit</a></td>';
                            table += '<td><a href="javascript:void(0)" class="DeleteRecoard" data-id="'+resultdata.categorydata[i].id+'">Delete</a></td>';
                            table += '</tr>';
                        }
                        $('#tablebody').html(table)
                    }
                }
            })
        }

        function itemdelete(id)
        {
            $.ajax({
                method:'POST',
                url:'item_Delete.php',
                dataType:'json',
                data:{id:id},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        alert(resultdata.message);
                        itemfetch()
                    }
                    else
                    {
                        alert(resultdata.message);
                    }
                }
            })
        }

        function itemedit(id)
        {
            $.ajax({
                method:'POST',
                url:'item_Edit.php',
                dataType:'json',
                data:{id:id},
                success:function(data)
                {
                    var jsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(jsonData);

                    if(resultdata.status == 1)
                    {
                        var catid = resultdata.catid;
                        $.ajax({
                            method:'POST',
                            url:'SubCategory_Fetch.php',
                            dataType:'json',
                            data:{catid:catid,flag:1},
                            success:function(data)
                            {
                                var jsonData = JSON.stringify(data);
                                var resultdata2 = jQuery.parseJSON(jsonData);
                                
                                if(resultdata2.status == 1)
                                {
                                    var table = '';
                                    table += '<option value = "">Select SubCategory</option>'
                                    for(var i in resultdata2.categorydata)
                                    {
                                        table += '<option value = "'+resultdata2.categorydata[i].id+'">'+resultdata2.categorydata[i].subcatname+'</option>'
                                        
                                    }
                                    $('#subcatid').html(table);
                                    $('#id').val(id);
                                    $('#catid').val(resultdata.catid);
                                    $('#subcatid').val(resultdata.subcatid);
                                    $('#itemname').val(resultdata.itemname);
                                    $('#itemno').val(resultdata.itemno);
                                    $('#itemamount').val(resultdata.itemamount);
                                    showformupdate()
                                }
                                
                            }
                            
                        });
                       
                        
                    }
                            
                }
                        
            });
         }
                            

        $('#mypage').validate({
            rules:{
                catid:{
                    required : true
                },
                subcatid:{
                    required : true
                },
                itemname:{
                    required : true
                },
                itemno:{
                    required : true
                },
                itemamount:{
                    number :true,
                    required : true
                }
            },

            messages:{
                catid:{
                    required : 'Please select category'
                },
                subcatid:{
                    required : 'Please select Subcategory'
                },
                itemname:{
                    required : 'Item name is required'
                },
                itemno:{
                    required: 'Item no is required'
                },
                itemamount:{
                    required: 'Item Amount is required',
                    number:'Please enter valid number'
                }
            },
            submitHandler:function(form)
            {
                if($('#id').val() == '')
                {
                    var url = 'item_insert.php';
                }
                else
                {
                    var url = 'item_update.php'
                }
                alert('Submited');

                var formdata = new FormData(form);
                $.ajax({
                    method:'POST',
                    url:url,
                    dataType:'json',
                    data:formdata,
                    processData: false,
                    contentType:false,
                    success:function(data)
                    {
                        var jsonData = JSON.stringify(data);
                        var resultdata = jQuery.parseJSON(jsonData);

                        if(resultdata.status == 1)
                        {
                            alert(resultdata.message)
                            itemfetch()
                            showformsubmit() 
                        }
                        else
                        {
                            alert(resultdata.message)
                        }
                    }
                })
            }
        })
    </script>
</body>
</html>