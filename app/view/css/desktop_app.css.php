<style>
    
    html{
        font-family: arial;
        background: whitesmoke;
    }
    
    body{
        position: relative;
        z-index: -1;
    }
    
@media only screen and (min-width: 768px) {
    
    .head{
        position: fixed;
        background: whitesmoke;
        height: 80px;
        width: 100%;
        left:0;
        top:0;
        z-index: 999;
        box-shadow: 0px 1px 0px rgb(0, 0, 0, .1);
    }
    
    .dashboad-container{
        background: ;
        position: relative;
        top: 60px;
        left: 60px;
        padding: 20px;
        width: 93%;
        height: auto;
        z-index: -1;
    }
    
    .sidebar{
        position: fixed;
        height: 100vh;
        width: 60px;
        background: ;
        left: 0;
        top:0;
        box-shadow: 1px 0px 0px rgb(0, 0, 0, .1);
    }
    
    .sidebar-container {
        position: fixed;
        height: 25%;
        width: 60px;
        left: 0;
        justify-content: space-evenly;
        align-items:center ;
        display: flex;
        flex-direction: column;
        top: 70px;
    }
    
    .sidebar-container svg{
        fill: gray;
    }
    
    .sidebar-container svg:hover{
        fill: green;
    }
    
    .sidebar-container  button{
        background: none;
        border: none;
        z-index: 999;
    }
    
    .reforestation h3{ 
        box-shadow: 0px 1px 0px rgb(0, 0, 0, .1);
        padding: 5px;
        font-weight: 400;
    }
    
    .refo-summary{
        height: 80px;
        margin-top: -5px;
        justify-content: space-around;
        align-items: stretch;
        display: flex;
    }
    
    .refo-info{
        width: 150px;
        text-align: center;
        padding: 5px;
    }
    
    h2{
        margin-top: 0px;
        font-size: 30px;
    }
    
    .p-month {
        padding: 10px;
        width: 60%;
        height: 400px;
        background: white;
        border-radius: 10px;
    }
    
    .p-sites{
        padding: 20px;
        width: 450px;
        height: 380px;
        background: white;
        border-radius: 10px;
        position: absolute;
        right: 50px;
        top: 180px;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }
    
    h5 {
        margin-bottom: 0;
        margin-top: 0;
        font-size: 20px;
    }
    
    .tools-container {
        position: absolute;
        justify-content: flex-end;
        align-items: stretch;
        display: flex;
        width: 96%;
    }
    
    .tools-container button{
        padding: 2px 20px 2px 20px;
        border-radius: 10px;
        margin: 3px;
        border: none;
        background: none;
        justify-content: center;
        align-items: center;
        display: flex;
    }
    
    .tools-container button:hover{
        fill: green;
        color: green;
    }
    
    .p-ranking{
        margin-top: 20px;
        height: 500px;
        width: 400px;
        position: relative;
        padding: 20px;
        background: white;
        border-radius: 10px;
        margin-right: 23px;
    }
    
    .user-monitoring{
        justify-content: flex-start;
        align-items: stretch;
        display: flex;
    }
    
    .D-container table {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
    }
    
    .D-container table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .D-container th, .D-container td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    
    .D-container th {
        background-color: #f2f2f2;
    }
    
    .D-container td:nth-child(2),
    .D-container td:nth-child(3),
    .D-container th:nth-child(2),
    .D-container th:nth-child(3){
        text-align: center;
    }
    
    .D-container{
        overflow-x: auto;
        height: 460px;
    }
    /* For WebKit browsers (Chrome, Safari) and Blink-based browsers */
    ::-webkit-scrollbar {
        width: 10px;
        
    }
    
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    
    ::-webkit-scrollbar-thumb {
        background-color: #dddd;
    }
    
    /* For Firefox */
    * {
        scrollbar-width: thin;
        scrollbar-color: darkgrey lightgrey;
    }
    
    /* For Internet Explorer and Edge */
    * {
        scrollbar-face-color: darkgrey;
        scrollbar-track-color: lightgrey;
        scrollbar-arrow-color: lightgrey;
        scrollbar-highlight-color: lightgrey;
        scrollbar-shadow-color: darkgrey;
        scrollbar-3dlight-color: lightgrey;
        scrollbar-darkshadow-color: darkgrey;
    }
    
    .nursery h3{ 
        box-shadow: 0px 1px 0px rgb(0, 0, 0, .1);
        padding: 5px;
        font-weight: 400;
    }
    
    .nur-summary{
        height: 80px;
        margin-top: -5px;
        justify-content: space-around;
        align-items: stretch;
        display: flex;
    }
    
    .nur-info{
        width: 150px;
        text-align: center;
        padding: 5px;
    }
    
    .n-month {
        padding: 10px;
        width: 60%;
        height: 400px;
        background: white;
        border-radius: 10px;
    }
    
    .n-sites{
        padding: 20px;
        width: 450px;
        height: 380px;
        background: white;
        border-radius: 10px;
        position: absolute;
        right: 50px;
        top: 180px;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }
    
    .n-sites{
        position: absolute;
        top: 13.9in;
    }
    
    .container{
        background: ;
        position: relative;
        top: 60px;
        left: 60px;
        padding: 20px;
        width: 93%;
        height: auto;
        z-index: -1;
    }
}

    .D-container tbody tr:hover {
        background: green;
        color: white;
        cursor: pointer;
    }
        
</style>