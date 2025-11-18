<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="{{route('blog.store')}}" method="POST">
    @csrf
    <label for="title">Title:</label>
    <input type="text" id="title" name="Title" required>

    <label for="description">Description:</label>
    <input type="text" id="description" name="discription" required>

  

    <input type="submit" value="Submit">
</form>
</body>

<style>
    form {
        max-width: 400px;
        margin: 40px auto;
        background: #fafafa;
        border: 1px solid #ddd;
        padding: 24px 32px;
        border-radius: 8px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.09);
    }
    label {
        font-weight: 500;
        display: block;
        margin-bottom: 6px;
        margin-top: 14px;
        color: #333;
    }
    input[type="text"] {
        width: 100%;
        padding: 8px 10px;
        margin-bottom: 18px;
        border: 1px solid #bdbdbd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 1rem;
        transition: border 0.2s;
    }
    input[type="text"]:focus {
        border: 1.5px solid #3097fd;
        outline: none;
    }
    input[type="submit"] {
        background: #3097fd;
        color: white;
        padding: 10px 22px;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 12px;
        transition: background 0.2s;
    }
    input[type="submit"]:hover {
        background: #226dc1;
    }
</style>
</body>
</html>