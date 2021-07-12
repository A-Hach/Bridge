var xhr = new XMLHttpRequest()

const host = window.location.hostname,
    socket = io('http://' + host + ':3000')

let messagesList = document.querySelector('#public-chat .messages-list'),
    messagesForm = document.querySelector('#public-chat form'),
    messageInput = document.querySelector('#public-chat form input'),
    user = {}

getConnectedUser()
scrollChatDown()

socket.on('chat-message', message => {
    appendMessage(message.text, 'left', message.username, message.picture)

    scrollChatDown()
})

socket.on('user-connected', name => {
    appendMessage(`<a href="http://${host}/bridge/u/${name}">@${name}</a> a rejoint la discussion`, 'notification')
})

socket.on('user-disconnected', name => {
    if (name !== null)
        appendMessage(`<a href="http://${host}/bridge/u/${name}">@${name}</a> a quitté la discussion`, 'notification')
})

socket.on('input-focus', () => {
    writingDOM()
})

socket.on('input-blur', () => {
    writingDOM()
})

messagesForm.addEventListener('submit', e => {
    e.preventDefault();

    let text = messageInput.value

    if (text == '')
        messageInput.focus()
    else {
        appendMessage(text, 'right', user.username, user.picture)
        socket.emit('send-chat-message', {
            text: text,
            username: user.username,
            picture: user.picture
        })
        messageInput.value = ''
        saveMessage(text)

        scrollChatDown()
    }
})

messageInput.addEventListener('focus', () => {
    socket.emit('writing')
})

messageInput.addEventListener('blur', () => {
    socket.emit('bluring')
})

/* ---------------- FUNCTIONS ---------------- */
function scrollChatDown() {
    messagesList.scrollTop = messagesList.scrollHeight

    let newMessageContainer = document.querySelector('#public-chat form .new-messages')
    if (newMessageContainer !== null)
        newMessageContainer.remove()
}

function getConnectedUser() {
    xhr.open('GET', 'http://' + host + '/bridge/data/getUser', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200)
            if (JSON.parse(xhr.responseText) != null) {
                user = JSON.parse(xhr.responseText)
                appendMessage('Vous avez rejoint', 'notification')
                socket.emit('new-user', user.username)
            }
    }

    xhr.send()
}

function saveMessage(text) {
    xhr.open('POST', 'http://' + host + '/bridge/data/saveMessage', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {}
    }

    xhr.send(`text=${text}`)
}

function appendMessage(text, type, username, picture) {
    let messageElement = document.createElement('li')

    messageElement.classList.add(type)
    if (type == 'notification')
        messageElement.innerHTML = text
    else {
        let messageContainer = document.createElement('div'),
            messageText = document.createElement('span'),
            messageSender = document.createElement('a'),
            imageElement = document.createElement('img'),
            elapsedTime = document.createElement('div')

        elapsedTime.classList.add('elapsed-time')
        elapsedTime.innerText = getCurrentDate()

        messageContainer.classList.add('text')
        messageText.innerText = text;
        messageContainer.appendChild(messageText)
        messageContainer.appendChild(elapsedTime)

        messageSender.href = `http://${host}/bridge/u/${username}`

        imageElement.src = `http://${host}/files.bridge/${picture}`
        imageElement.setAttribute('title', username)
        messageSender.appendChild(imageElement)

        messageElement.appendChild(messageContainer)
        messageElement.appendChild(messageSender)
    }

    // Append message
    messagesList.appendChild(messageElement)
}

function getCurrentDate() {
    let today = new Date(),
        dd = today.getDate(),
        mm = today.getMonth() + 1,
        yyyy = today.getFullYear();

    if (dd < 10)
        dd = '0' + dd

    if (mm < 10)
        mm = '0' + mm

    return dd + '/' + mm + '/' + yyyy
}

function writingDOM() {
    let writingCircles = document.querySelector('#public-chat form .writing'),
        newMessageContainer = document.querySelector('#public-chat form .new-messages')

    if (newMessageContainer === null) {
        if (writingCircles !== null)
            document.querySelector('#public-chat form .writing').remove()
        else {
            let writingContainer = document.createElement('div'),
                leftCircle = document.createElement('div'),
                centerCircle = document.createElement('div'),
                rightCircle = document.createElement('div')

            writingContainer.classList.add('writing')
            writingContainer.appendChild(leftCircle)
            writingContainer.appendChild(centerCircle)
            writingContainer.appendChild(rightCircle)

            messagesForm.appendChild(writingContainer)
        }
    }
}

function newMessagesFlash() {
    let writingCircles = document.querySelector('#public-chat form .writing')
    if (writingCircles !== null)
        writingDOM()

    let newMessageContainer = document.querySelector('#public-chat form .new-messages')

    if (newMessageContainer === null) {
        let newMessageContainer = document.createElement('div'),
            newMessagesInner = document.createElement('span')

        newMessagesInner.innerText = 'Nouveaux messages'
        newMessageContainer.classList.add('new-messages')
        newMessageContainer.appendChild(newMessagesInner)
        newMessageContainer.addEventListener('click', scrollChatDown)

        messagesForm.appendChild(newMessageContainer)
    }
}