      $(document).ready(function(){
          $('.delBtn').click(function(){
          var ID=$(this).data('value');
          console.log(ID);
			    $.ajax({
			    	type: 'POST',
			       url: 'functions.php',
             data: { key: ID },
              success: function (output) {
                
              }

			  	  });
          $(this).parent().parent().parent().remove();
          });
        });
      $(document).ready(function(){
        $('input#accountSubmit').click( function() {
           var accId = $(this).data('value')
           console.log(accId);
            $.ajax({
                url: 'functions.php',
                type: 'post',
                dataType: 'json',
                data: $('form#accountForm').serialize() + "&accId="+accId,
                success: function(data) {
                    console.log(data);

                           }
            });
        });
      });
      $(document).ready(function(){
        $('input#addUser').click( function() {
            $.ajax({
                url: 'functions.php',
                type: 'post',
                dataType: 'json',
                data: $('form#addUserForm').serialize(),
                success: function(data) {
                    console.log(data);

                           }
            });
                     // history.go(0)
        });
      });
      $(document).ready(function(){
        $('input#transactionSubmit').click( function() {
            $.ajax({
                url: 'functions.php',
                type: 'post',
                dataType: 'json',
                data: $('form#transactionForm').serialize(),
                success: function(data) {
                              window.location.href='main.php#'
                             }

            });
            window.location.href='main.php#'
        });
      });


   function rmRequiredAddUser(){
      $('#addUserForm').removeAttr('required'); 
    }
       function rmRequiredTransaction(){
      $('#transactionForm').removeAttr('required'); 
    }
   $(document).ready(function(){
       $('.addBtn').click(function(){
      var val = $(this).data('value');
      document.getElementById("accountSubmit").setAttribute("data-value", val);
      console.log(val);
       });
    }); 

