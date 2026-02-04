# Custom Development Agents

This directory contains custom GitHub Copilot agents specifically designed for the product-store project's tech stack.

## Available Agents

### 🐳 docker-expert
**Name**: `docker-expert`  
**Description**: Expert in Docker, Docker Compose, containerization, and deployment configurations

**Use for**:
- Creating and optimizing docker-compose.yml files
- Writing and improving Dockerfiles
- Container networking and service orchestration
- Volume and data persistence configuration
- Docker security and performance optimization
- Troubleshooting container issues

### 🚀 laravel-expert
**Name**: `laravel-expert`  
**Description**: Expert in Laravel PHP framework, API development, Eloquent ORM, and backend architecture

**Use for**:
- Laravel API development and RESTful endpoints
- Eloquent models, relationships, and migrations
- Authentication and authorization (Sanctum/Passport)
- Request validation and form requests
- Query optimization and caching
- Laravel testing (PHPUnit)
- Backend business logic implementation

### 🐘 postgres-expert
**Name**: `postgres-expert`  
**Description**: Expert in PostgreSQL database design, optimization, queries, and administration

**Use for**:
- Database schema design and modeling
- SQL query optimization and performance tuning
- PostgreSQL-specific features (JSONB, arrays, full-text search)
- Index creation and optimization
- Migration review and optimization
- Database constraints and data integrity
- Backup and recovery strategies

### 🎨 vue-tailwind-expert
**Name**: `vue-tailwind-expert`  
**Description**: Expert in Vue.js 3, Composition API, Tailwind CSS, and modern frontend development

**Use for**:
- Vue.js 3 component development (Composition API)
- Tailwind CSS styling and responsive design
- Vue Router setup and navigation
- State management with Pinia
- API integration with Laravel backend
- Form handling and validation
- Frontend performance optimization
- Component testing

### 🔗 fullstack-integrator
**Name**: `fullstack-integrator`  
**Description**: Expert in full-stack integration, coordinating Docker, Laravel, PostgreSQL, and Vue.js+Tailwind stack

**Use for**:
- Complete project setup and initialization
- Full-stack architecture decisions
- Integrating Vue.js frontend with Laravel backend
- End-to-end authentication implementation
- CORS and API communication setup
- Multi-service Docker orchestration
- Environment configuration across services
- Cross-stack debugging and troubleshooting
- Deployment strategies

## Tech Stack

This project uses:
- **Frontend**: Vue.js 3 + Tailwind CSS
- **Backend**: Laravel (PHP)
- **Database**: PostgreSQL
- **Containerization**: Docker Compose

## How to Use These Agents

### In GitHub Copilot Chat

You can invoke these agents in GitHub Copilot Chat using the `@` mention syntax:

```
@docker-expert Create a docker-compose.yml file for Laravel, PostgreSQL, and Vue.js

@laravel-expert Set up a Product model with CRUD API endpoints

@postgres-expert Design a database schema for an e-commerce product catalog

@vue-tailwind-expert Create a responsive product listing component

@fullstack-integrator Set up the complete development environment with authentication
```

### Choosing the Right Agent

- **Single technology task**: Use the specific expert (docker-expert, laravel-expert, etc.)
- **Multi-technology integration**: Use fullstack-integrator
- **Not sure**: Start with fullstack-integrator; it can coordinate with other agents

### Examples

#### Example 1: Setting up a new feature
```
@fullstack-integrator I need to add a shopping cart feature. 
Set up the database schema, Laravel API, and Vue.js components.
```

#### Example 2: Optimizing performance
```
@postgres-expert The product search query is slow. 
Here's the query: [paste query]
Can you optimize it?
```

#### Example 3: Docker issues
```
@docker-expert The Laravel container can't connect to PostgreSQL. 
Here's my docker-compose.yml: [paste file]
```

#### Example 4: Frontend component
```
@vue-tailwind-expert Create a product card component with 
image, title, price, and "Add to Cart" button using Tailwind CSS
```

## Agent Capabilities

Each agent has:
- ✅ Deep expertise in their specific domain
- ✅ Knowledge of best practices and patterns
- ✅ Understanding of the complete tech stack context
- ✅ Ability to write, review, and optimize code
- ✅ Debugging and troubleshooting skills
- ✅ Documentation capabilities

## Best Practices

1. **Be specific**: Provide context and clear requirements
2. **Share code**: Include relevant code snippets or file paths
3. **Iterate**: Agents can refine solutions based on feedback
4. **Combine agents**: Use fullstack-integrator for cross-cutting concerns
5. **Error messages**: Always include full error messages when debugging

## Contributing

When modifying agents:
1. Keep expertise focused and clear
2. Update guidelines based on project evolution
3. Add new agents as the tech stack grows
4. Test agent responses for quality

## Support

For issues or questions about these agents, please open an issue in the repository.
