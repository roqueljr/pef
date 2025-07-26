<style>
    loader {
        display: none;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -20px;
        margin-left: -20px;
        z-index: 9999; /* Set a high z-index value */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    html{
        font-family: arial;
    }
    
    .name {
        width: 100px;
    }
    
    .form-container{
        justify-content: center;
        align-items: center;
        display: flex;
        width: 100%;
        height:auto;
    }
    
    form {
        position: absolute;
        left:0;
        top:40px;
        width: 100%;
        height: auto;
    }
    
    table{
        width: 100%;
        padding:10px;
    }
    
    table input, select{
        width: 100%;
        padding: 10px;
    }
    
    .qr_no{
        position: absolute;
        top: -35px;
        width: 118%;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        background: whitesmoke;
        padding: 10px 0px 10px 0px;
    }
    
    .head{
        position: absolute;
        background: green;
        width: 101vw;
        left: 0;
        top: 0;
    }
    
    button {
        padding: 5px;
        width: 80%;
        font-size: 18px;
    }

    .btn {
        padding-top:10px;
        text-align: center;
    }
    
    .btn2 {
        padding-top:10px;
        text-align: center;
    }
    
    .footer{
        position: fixed;
        bottom: 0px;
        padding: 20px;
        left: 0;
        width: 100%; 
        height: 100px;
        text-align: center;
        background: whitesmoke;
    }
    
    svg {
        position: fixed;
        left: 10px;
        top: 10px;
    }
</style>