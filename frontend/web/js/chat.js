


window.onload = function() {

    var  socket = new WebSocket('ws://10.0.0.151:8081');


        var status = document.getElementById('status');
        //console.log(status);

        socket.onopen = function (event) {
            //alert(11111);
            status.innerHTML = 'connected';
        };


        socket.onclose = function (event) {
            console.log(event);

            if (event.wasClean) {
                status.innerHTML = 'closed';
            } else {
                status.innerHTML = 'closed some';
            }
        };



        socket.onmessage = function (event) {
            console.log(event);

            let mess = JSON.parse(event.data);
            var div = document.getElementById('messages-field');
            var innerDiv = document.createElement('div');
            innerDiv.classList.add('leftmessage');
            var h3 = document.createElement('h3');
            h3.innerHTML = `${mess.name}: ${mess.msg}`;
            innerDiv.appendChild(h3);
            div.appendChild(innerDiv);
            document.getElementById('nameField').value = '';
            document.getElementById('textField').value = '';
        };


        socket.onerror = function (event) {
            status.innerHTML = 'error ' + event.message;
        };


        document.forms['messages'].onsubmit = function () {
            let message = {
                name: this.fname.value,
                msg: this.msg.value
            }
            socket.send(JSON.stringify(message));
            // return false;
        }


        // return false;
    };
