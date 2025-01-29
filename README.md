Tesk task:
# Тестовое задание на позицию php разработчика

Разработать REST API для управления списком задач . 

### Функционал приложения:
1. Создание задачи: 
   Задача должна включать следующие поля:
   - Название (строка, обязательно, до 255 символов).
   - Описание (текст, опционально, без ограничений).
   - Срок выполнения (дата и время).
   - Дата создания (дата и время).
   - Статус (выполнена/не выполнена).
   - Приоритет (низкий, средний, высокий).
   - Категория (например, "Работа", "Дом", "Личное").
   

2. Просмотр списка задач:
   - Возможность поиска по названию .
   - Сортировка по дате выполнения .

3. Редактирование задачи: возможность изменить любое из полей.

4. Удаление задачи.

### Пример структуры методов API

#### 1. Работа с задачами

- Создание задачи  
  POST /api/tasks  
  Описание: Создает новую задачу.  
  Тело запроса:  
  
  {
    "title": "Задача1",
    "description": "Задача1 описание",
    "due_date": "2025-01-20T15:00:00",
    "create_date": "2025-01-20T15:00:00",
    "priority": "высокий",
    "category": "Работа",
    "status": "не выполнена"
  }
  
  
  Ответ:  
  
  {
    "id": 1,
    "message": "Task created successfully"
  }

- Получение списка задач  
  GET /api/tasks  
  Описание: Возвращает список задач с возможностью поиска и сортировки.  
  Параметры запроса (опционально):
  - search: поиск по названию.
  - sort: due_date, created_at.  

  Пример запроса:  
  /api/tasks?search=Задача1&sort=due_date  

  Ответ:  
  
  [
    {
      "id": 1,
      "title": "Задача1",
      "description": "Задача1 описание",
      "due_date": "2025-01-20T15:00:00",
      "create_date": "2025-01-20T15:00:00",
      "status": "pending",
      "priority": "высокий",
      "category": "Работа",
      "status": "не выполнена"
    }
  ]
  

- Получение конкретной задачи  
  GET /api/tasks/{id}  
  Описание: Возвращает задачу по её ID.  
  Ответ:  
  
  {
    "id": 1,
    "title": "Задача1",
    "description": "Задача1 описание",
    "due_date": "2025-01-20T15:00:00",
    "create_date": "2025-01-20T15:00:00",
    "status": "pending",
    "priority": "высокий",
    "category": "Работа",
    "status": "не выполнена"
  }
  

- Обновление задачи  
  PUT /api/tasks/{id}  
  Описание: Обновляет информацию о задаче.  
  Тело запроса:  
  
  {
    "title": "Задача2",
    "description": "Задача2 описание обновленное",
    "due_date": "2025-01-25T18:00:00",
    "priority": "низкий",
    "status": "выполнена"
  }
  
  
  Ответ:  
  
  {
    "message": "Task updated successfully"
  }
  

- Удаление задачи  
  DELETE /api/tasks/{id}  
  Описание: Удаляет задачу по её ID.  
  Ответ:  
  
  {
    "message": "Task deleted successfully"
  }
  

### Критерии оценки:
1. Чистота и структура кода.
2. Следование стандартам и лучшим практикам.
3. Полнота выполнения функциональных требований.
4. Документация и инструкции.
5. Качество тестов.


Нужна возможность выводить информацию в списке постранично   

Swagger для отображения методов api (https://swagger.io/)
