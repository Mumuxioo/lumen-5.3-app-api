define({ "api": [
  {
    "type": "post",
    "url": "/auth/token/new",
    "title": "刷新token(refresh token)",
    "description": "<p>刷新token(refresh token)</p>",
    "group": "Auth",
    "permission": [
      {
        "name": "JWT"
      }
    ],
    "version": "0.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>用户旧的jwt-token, value已Bearer开头</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n   \"status\": \"success\",\n   \"status_code\": 200,\n   \"message\": \"操作成功\",\n   \"data\": {\n       \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWwubHVtZW41LjMuY29tL2FwaS9hdXRoL3Rva2VuL25ldyIsImlhdCI6MTQ3NDA5NzE2NCwiZXhwIjoxNDc0MTAwNzc1LCJuYmYiOjE0NzQwOTcxNzUsImp0aSI6ImNhODliMTUxNWNkZmNmN2ExY2I0MjZkMGE2MDUzMjllIiwic3ViIjo2fQ.EfPEd6tE9Ui9vfj7dcs9twsv8INW2THhnsqGPO-xXQQ\"\n   }\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostAuthTokenNew"
  },
  {
    "type": "post",
    "url": "/authorization",
    "title": "登录(login)",
    "description": "<p>登录(login)</p>",
    "group": "Auth",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Phone",
            "optional": false,
            "field": "phone",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n{\n  \"status\": \"success\",\n  \"code\": 200,\n  \"message\": \"登陆成功\",\n  \"data\": {\n   \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWwubHVtZW41LjMuY29tL2FwaS9hdXRob3JpemF0aW9uIiwiaWF0IjoxNDc0MDkwNTAwLCJleHAiOjE0NzQwOTQxMDAsIm5iZiI6MTQ3NDA5MDUwMCwianRpIjoiZTNmNzU0NjcwZjY3YmJmMjFiNjNkOTEwMTM5ZWE3YTQiLCJzdWIiOjZ9.gXZlIG7JOi2xX11fawLV6ID4WpTOdp5VKvwPfLFsHXU\"\n       }\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n    \"status\": \"error\",\n    \"code\": 403,\n    \"message\": {\n    \"user_phone\": [\n          \"手机号格式不正确\"\n          ]\n      }\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostAuthorization"
  },
  {
    "type": "post",
    "url": "/user/register",
    "title": "注册(register)",
    "description": "<p>注册(register)</p>",
    "group": "Auth",
    "permission": [
      {
        "name": "none"
      }
    ],
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Phone",
            "optional": false,
            "field": "phone",
            "description": "<p>phone[unique]</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>password</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone_code",
            "description": "<p>phone_code 验证码</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"status\": \"success\",\n     \"status_code\": 200,\n     \"message\": \"注册成功\",\n     \"data\": {\n         \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY\"\n     }\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 403 Bad Request\n {\n   \"status\": \"error\",\n   \"status_code\": 403,\n   \"message\": {\n       \"user_phone\": [\n       \"手机号已注册\"\n       ]\n   },\n   \"data\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostUserRegister"
  },
  {
    "type": "post",
    "url": "/user/sendSMS",
    "title": "发送手机验证码(sendSMS)",
    "description": "<p>手机验证码(sendSMS)</p>",
    "group": "Auth",
    "permission": [
      {
        "name": "none"
      }
    ],
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "user_phone",
            "optional": false,
            "field": "user_phone",
            "description": "<p>user_phone</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"status\": \"success\",\n     \"status_code\": 200,\n     \"message\": \"发送成功\",\n     \"data\": \"316954\"\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 403 Bad Request\n {\n   \"status\": \"error\",\n   \"status_code\": 403,\n   \"message\": {\n       \"user_phone\": [\n           \"手机号不能为空\"\n       ]\n   },\n   \"data\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "Auth",
    "name": "PostUserSendsms"
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./public/apidoc/main.js",
    "group": "E__Laravel_lumen_5_3_public_apidoc_main_js",
    "groupTitle": "E__Laravel_lumen_5_3_public_apidoc_main_js",
    "name": ""
  },
  {
    "type": "delete",
    "url": "/posts/{id}",
    "title": "删除帖子(delete post)",
    "description": "<p>删除帖子(delete post)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "jwt"
      }
    ],
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 NO CONTENT",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "DeletePostsId"
  },
  {
    "type": "delete",
    "url": "/posts/{postId}/comments/{id}",
    "title": "删除评论(delete post comment)",
    "description": "<p>删除评论(delete post comment)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "jwt"
      }
    ],
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 NO CONTENT",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostCommentController.php",
    "groupTitle": "Post",
    "name": "DeletePostsPostidCommentsId"
  },
  {
    "type": "get",
    "url": "/posts",
    "title": "帖子列表(post list)",
    "description": "<p>帖子列表(post list)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "'comments:limit(x)'",
              "'user'"
            ],
            "optional": true,
            "field": "include",
            "description": "<p>include</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"data\": [\n    {\n      \"id\": 1,\n      \"user_id\": 3,\n      \"title\": \"foo\",\n      \"content\": \"\",\n      \"created_at\": \"2016-03-30 15:36:30\",\n      \"user\": {\n        \"data\": {\n          \"id\": 3,\n          \"email\": \"foo@bar.com1\",\n          \"name\": \"\",\n          \"avatar\": \"\",\n          \"created_at\": \"2016-03-30 15:34:01\",\n          \"updated_at\": \"2016-03-30 15:34:01\",\n          \"deleted_at\": null\n        }\n      },\n      \"comments\": {\n        \"data\": [],\n        \"meta\": {\n          \"total\": 0\n        }\n      }\n    }\n  ],\n  \"meta\": {\n    \"pagination\": {\n      \"total\": 2,\n      \"count\": 2,\n      \"per_page\": 15,\n      \"current_page\": 1,\n      \"total_pages\": 1,\n      \"links\": []\n    }\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "GetPosts"
  },
  {
    "type": "get",
    "url": "/posts/{id}",
    "title": "帖子详情(post detail)",
    "description": "<p>帖子详情(post detail)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "'comments'",
              "'user'"
            ],
            "optional": true,
            "field": "include",
            "description": "<p>include</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"data\": {\n    \"id\": 1,\n    \"user_id\": 3,\n    \"title\": \"foo\",\n    \"content\": \"\",\n    \"created_at\": \"2016-03-30 15:36:30\",\n    \"user\": {\n      \"data\": {\n        \"id\": 3,\n        \"email\": \"foo@bar.com1\",\n        \"name\": \"\",\n        \"avatar\": \"\",\n        \"created_at\": \"2016-03-30 15:34:01\",\n        \"updated_at\": \"2016-03-30 15:34:01\",\n        \"deleted_at\": null\n      }\n    },\n    \"comments\": {\n      \"data\": [\n        {\n          \"id\": 1,\n          \"post_id\": 1,\n          \"user_id\": 1,\n          \"reply_user_id\": 0,\n          \"content\": \"foobar\",\n          \"created_at\": \"2016-04-06 14:51:34\"\n        }\n      ],\n      \"meta\": {\n        \"total\": 1\n      }\n    }\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "GetPostsId"
  },
  {
    "type": "get",
    "url": "/posts/{postId}/comments",
    "title": "评论列表(post comment list)",
    "description": "<p>评论列表(post comment list)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "'user'"
            ],
            "optional": false,
            "field": "include",
            "description": "<p>include</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": " HTTP/1.1 200 OK\n {\n  \"data\": [\n    {\n      \"id\": 1,\n      \"post_id\": 1,\n      \"user_id\": 1,\n      \"reply_user_id\": 0,\n      \"content\": \"foobar\",\n      \"created_at\": \"2016-04-06 14:51:34\",\n      \"user\": {\n        \"data\": {\n          \"id\": 1,\n          \"email\": \"foo@bar.com\",\n          \"name\": \"foobar\",\n          \"avatar\": \"\",\n          \"created_at\": \"2016-01-28 07:23:37\",\n          \"updated_at\": \"2016-01-28 07:24:05\",\n          \"deleted_at\": null\n        }\n      }\n    },\n    {\n      \"id\": 2,\n      \"post_id\": 1,\n      \"user_id\": 1,\n      \"reply_user_id\": 0,\n      \"content\": \"foobar1\",\n      \"created_at\": \"2016-04-06 15:10:22\",\n      \"user\": {\n        \"data\": {\n          \"id\": 1,\n          \"email\": \"foo@bar.com\",\n          \"name\": \"foobar\",\n          \"avatar\": \"\",\n          \"created_at\": \"2016-01-28 07:23:37\",\n          \"updated_at\": \"2016-01-28 07:24:05\",\n          \"deleted_at\": null\n        }\n      }\n    },\n    {\n      \"id\": 3,\n      \"post_id\": 1,\n      \"user_id\": 1,\n      \"reply_user_id\": 0,\n      \"content\": \"foobar2\",\n      \"created_at\": \"2016-04-06 15:10:23\",\n      \"user\": {\n        \"data\": {\n          \"id\": 1,\n          \"email\": \"foo@bar.com\",\n          \"name\": \"foobar\",\n          \"avatar\": \"\",\n          \"created_at\": \"2016-01-28 07:23:37\",\n          \"updated_at\": \"2016-01-28 07:24:05\",\n          \"deleted_at\": null\n        }\n      }\n    }\n  ],\n  \"meta\": {\n    \"pagination\": {\n      \"total\": 3,\n      \"count\": 3,\n      \"per_page\": 15,\n      \"current_page\": 1,\n      \"total_pages\": 1,\n      \"links\": []\n    }\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostCommentController.php",
    "groupTitle": "Post",
    "name": "GetPostsPostidComments"
  },
  {
    "type": "get",
    "url": "/user/posts",
    "title": "我的帖子列表(my post list)",
    "description": "<p>我的帖子列表(my post list)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "'comments:limit(x)'"
            ],
            "optional": true,
            "field": "include",
            "description": "<p>include</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"data\": [\n    {\n      \"id\": 1,\n      \"user_id\": 3,\n      \"title\": \"foo\",\n      \"content\": \"\",\n      \"created_at\": \"2016-03-30 15:36:30\",\n      \"user\": {\n        \"data\": {\n          \"id\": 3,\n          \"email\": \"foo@bar.com1\",\n          \"name\": \"\",\n          \"avatar\": \"\",\n          \"created_at\": \"2016-03-30 15:34:01\",\n          \"updated_at\": \"2016-03-30 15:34:01\",\n          \"deleted_at\": null\n        }\n      },\n      \"comments\": {\n        \"data\": [],\n        \"meta\": {\n          \"total\": 0\n        }\n      }\n    }\n  ],\n  \"meta\": {\n    \"pagination\": {\n      \"total\": 2,\n      \"count\": 2,\n      \"per_page\": 15,\n      \"current_page\": 1,\n      \"total_pages\": 1,\n      \"links\": []\n    }\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "GetUserPosts"
  },
  {
    "type": "post",
    "url": "/posts",
    "title": "发布帖子(create post)",
    "description": "<p>发布帖子(create post)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "jwt"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>post title</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>post content</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "PostPosts"
  },
  {
    "type": "post",
    "url": "/posts/{postId}/comments",
    "title": "发布评论(create post comment)",
    "description": "<p>发布评论(create post comment)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "jwt"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>post content</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostCommentController.php",
    "groupTitle": "Post",
    "name": "PostPostsPostidComments"
  },
  {
    "type": "put",
    "url": "/posts/{id}",
    "title": "修改帖子(update post)",
    "description": "<p>修改帖子(update post)</p>",
    "group": "Post",
    "permission": [
      {
        "name": "jwt"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>post title</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>post content</p>"
          }
        ]
      }
    },
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 NO CONTENT",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/PostController.php",
    "groupTitle": "Post",
    "name": "PutPostsId"
  },
  {
    "type": "get",
    "url": "/user",
    "title": "当前用户信息(current user info)",
    "description": "<p>当前用户信息(current user info)</p>",
    "group": "user",
    "permission": [
      {
        "name": "JWT"
      }
    ],
    "version": "0.1.0",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"data\": {\n    \"id\": 2,\n    \"email\": 'liyu01989@gmail.com',\n    \"name\": \"foobar\",\n    \"created_at\": \"2015-09-08 09:13:57\",\n    \"updated_at\": \"2015-09-08 09:13:57\",\n    \"deleted_at\": null\n  }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "user",
    "name": "GetUser"
  },
  {
    "type": "patch",
    "url": "/user",
    "title": "修改个人信息(update my info)",
    "description": "<p>修改个人信息(update my info)</p>",
    "group": "user",
    "permission": [
      {
        "name": "JWT"
      }
    ],
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>name</p>"
          },
          {
            "group": "Parameter",
            "type": "Url",
            "optional": true,
            "field": "avatar",
            "description": "<p>avatar</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n   \"id\": 2,\n   \"email\": 'liyu01989@gmail.com',\n   \"name\": \"ffff\",\n   \"created_at\": \"2015-10-28 07:30:56\",\n   \"updated_at\": \"2015-10-28 09:42:43\",\n   \"deleted_at\": null,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "user",
    "name": "PatchUser"
  },
  {
    "type": "put",
    "url": "/user/password",
    "title": "修改密码(edit password)",
    "description": "<p>修改密码(edit password)</p>",
    "group": "user",
    "permission": [
      {
        "name": "JWT"
      }
    ],
    "version": "0.1.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "old_password",
            "description": "<p>旧密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password_confirmation",
            "description": "<p>确认新密码</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"password\": [\n        \"两次输入的密码不一致\",\n        \"新旧密码不能相同\"\n    ],\n    \"password_confirmation\": [\n        \"两次输入的密码不一致\"\n    ],\n    \"old_password\": [\n        \"密码错误\"\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "user",
    "name": "PutUserPassword"
  }
] });
