<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page Builder</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 420px;
            margin: auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #888;
        }

        .canvas-buttons {
            margin-bottom: 1rem;
        }

        .canvas-buttons button {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            border: none;
            background-color: #388a3b;
            color: #fff;
        }

        .canvas-buttons button:hover {
            background-color: #2f7431;
            transform: scale(1.03);
        }

        .select-primary {
            width: 100%;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <div class="container">
        <h2>Create or Select a Site</h2>
        <form id="siteTemplateForm" action="{{ route('process-builder') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="new-site">Create New Site</label>
                <input type="text" id="new-site" name="new_site" placeholder="Enter new site name">
            </div>

            <div class="divider">or</div>

            <div class="form-group">
                <label for="existing-site">Select Existing Site</label>
                <select id="existing-site" name="existing_site" class="select-primary">
                    <option value="">-- Choose a site --</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->name }}</option>
                    @endforeach
                </select>
            </div>

            <hr style="height: 2px; margin: 2rem 0; background-color: #CCCCCC; border: none;">

            <div class="form-group">
                <label for="template-id">Select Template</label>
                <select id="template-id" name="template_id" class="select-primary">
                    <option value="">-- Choose a template --</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" data-slug="{{ $template->slug }}">{{ $template->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="canvas-buttons">
                <button type="submit" class="btn-primary">Continue</button>
            </div>

        </form>
    </div>

</body>

</html>
