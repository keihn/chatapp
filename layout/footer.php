<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>



<script>
    (async function checkUserActivity() {

        try {
            var response = await fetch("http://chatapp.test/api/user_activity.php")
            if (!response.ok) {
                throw new Error(response.status)
            }

            console.log(response.json())
        } catch (error) {
            console.error(error.message)
        }

        // setInterval(checkUserActivity, 50000)
    }())
</script>



</body>

</html>