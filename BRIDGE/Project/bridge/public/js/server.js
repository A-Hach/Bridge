const io = require('socket.io')(3000)
let users = {}

io.on('connection', socket => {
    socket.on('send-chat-message', message => {
        socket.broadcast.emit('chat-message', message)
    })

    socket.on('new-user', name => {
        users[socket.id] = name
        socket.broadcast.emit('user-connected', name)
    })

    socket.on('writing', () => {
        socket.broadcast.emit('input-focus')
    })

    socket.on('bluring', () => {
        socket.broadcast.emit('input-blur')
    })

    socket.on('disconnect', () => {
        socket.broadcast.emit('user-disconnected', users[socket.id])
        delete users[socket.id]
    })
})