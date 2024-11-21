<script>
        let timeout=null;
        let interval=null;

        document.addEventListener('mousemove', () => {
            if(timeout!==null){
                clearTimeout(timeout);
            }
            if(interval!==null){
                clearInterval(interval);
            }

            timeout= setTimeout(function() {
                let timer=300;

                interval=setInterval(function() {
                    timer--;
                    if(timer===-1){
                        clearInterval(interval);
                        window.location.href='logout.php';
                    }
                }, 1000);
                
            }, 100);
        });
    </script>