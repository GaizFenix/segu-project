function mouseMove() {
    document.addEventListener('mousemove', function(event) {
        var x = event.clientX;
        var y = event.clientY;
        console.log('Mouse position: X=' + x + ', Y=' + y);
    });
}