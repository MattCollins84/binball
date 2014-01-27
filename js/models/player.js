var PlayerModels = function() {
  
  return (function() {

    return {

      create: function(name, email, callback) {

        $.ajax({
          type: "GET",
          url: "/create/player",
          data: { name: name, email: email }
        })
        .done(function( msg ) {
           
          return callback(msg);

        });

      }

    }

  })();

}