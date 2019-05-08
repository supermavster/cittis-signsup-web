var firebaseConfig = {
    apiKey: "AIzaSyBG2O1geZCUdA22mOU78GYtiCAwsiI__xg\n",
    authDomain: "cittis-tracking.firebaseapp.com",
    databaseURL: "https://cittis-tracking.firebaseio.com",
    projectId: "cittis-tracking",
    storageBucket: "cittis-tracking.appspot.com",
    messagingSenderId: "sender-id",
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

//hang on event of form with id=myform
$("#submitLogin").click(function (e) {

    //prevent Default functionality
    e.preventDefault();
    console.log("inside");
    //get the action-url of the form
    var actionurl = e.currentTarget.action;

    var email = $("#email").val();
    var password = $("#password").val();
    firebase.auth().signInWithEmailAndPassword(email, password)
        .then(function (result) {
            // Password reset email sent.
            // The firebase.User instance:
            var user = result.user;

            console.log("isLogin" + user.displayName);
        })
        .catch(function (error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            if (errorCode === 'auth/wrong-password') {
                alert('Wrong password.');
            } else {
                alert(errorMessage);
            }
            console.log(error);
        });

});
