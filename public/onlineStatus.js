
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
