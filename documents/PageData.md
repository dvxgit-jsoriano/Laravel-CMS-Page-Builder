# Page Data for CMS Page Builder

## Example Block Structure

### Sample 1: (Hero Block)
```json
{
    "page_id": 2,
    "type": "hero",
    "position": 2,
    "block_fields": [
        {
            "field_key": "name",
            "field_value": "Hero Block",
            "field_type": "text"
        },
        {
            "field_key": "title",
            "field_value": "Welcome to our site!",
            "field_type": "text"
        },
        {
            "field_key": "sub_title",
            "field_value": "A place to showcase your products.",
            "field_type": "text"
        },
        {
            "field_key": "description",
            "field_value": "This is a hero section with catchy text and an attractive image.",
            "field_type": "textarea"
        }
    ]
}
```

### Sample 2: (Navigation Block)
```json
{
    "page_id": 2,
    "type": "navigation",
    "position": 1,
    "block_fields": [
        {
            "field_key": "name",
            "field_value": "My Company",
            "field_type": "text"
        },
        {
            "field_key": "title",
            "field_value": "Welcome to our site!",
            "field_type": "text"
        },
        {
            "field_key": "sub_title",
            "field_value": "A place to showcase your products.",
            "field_type": "text"
        },
        {
            "field_key": "description",
            "field_value": "This is a hero section with catchy text and an attractive image.",
            "field_type": "textarea"
        }
    ],
    "block_field_groups": [
        {
            "group_name": "center_links",
            "items": [
                {
                    "label": "Home",
                    "url": "/home"
                },
                {
                    "label": "Pricing",
                    "url": "/pricing"
                },
                {
                    "label": "Contact",
                    "url": "/contact"
                }
            ]
        }
    ]
}
```

```json
[
  {
    "type": "text",
    "id": "title",
    "name": "title",
    "label": "Title",
    "value": "My Title"
  },
  {
    "type": "textarea",
    "id": "desc",
    "name": "desc",
    "label": "Description",
    "value": "Some text..."
  },
  {
    "type": "select",
    "id": "type",
    "name": "type",
    "label": "Type",
    "value": "blog",
    "options": [
      {
        "value": "news",
        "label": "News"
      },
      {
        "value": "blog",
        "label": "Blog"
      }
    ]
  }
]
```
