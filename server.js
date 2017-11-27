var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var mysql = require('mysql');
var mysql = require('mysql')
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'root',
  database : 'myChat'
});

connection.connect();

server.listen(3000);

io.on('connection', function(socket){

  socket.on('subscribe', function(room) {
      console.log(socket.id,' joining room ', room);
      socket.join(room);
      socket.on('chatInfo', function(chatInfo) {
        if (chatInfo.userType == 'User') {
          var query = 'INSERT INTO `rooms`(`name`, `users`, `username`, `email`, `mobile`) VALUES ("' +room+ '","' +socket.id+ '","' +chatInfo.name+ '","' +chatInfo.email+ '","' +chatInfo.mobile+ '")';
          connection.query(query, function (err, rows, fields) {
          if (err) throw err

          })
        }
      })

  })


  socket.on('unsubscribe', function(room) {
      console.log(socket.id,'leaving room', room);
      socket.leave(room);
  })

  socket.on('send', function(data) {
      // console.log(data);
      io.sockets.in(data.room).emit('message', data);
      var query = 'SELECT * FROM `rooms` WHERE `name` = "'+data.room+'"';
      connection.query(query, function (err, rows, fields) {
      if (err) throw err
          for (var i = 0, len = rows.length; i < len; i++) {
            if (rows[i].is_active == 0) {
              console.log('send message to mobile');
            }
          }
      })
  });

  socket.on('disconnect', function(room)  {
    console.log(socket.id,'disconnected', room);
    var query = 'UPDATE `rooms` SET `is_active`="0" WHERE `users`="'+socket.id+'"';
    connection.query(query, function (err, rows, fields) {
    if (err) throw err
      // console.log(socket.id, '')
    })
  });


});
