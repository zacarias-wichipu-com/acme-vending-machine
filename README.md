## Setup

### Tools

1. [Git](https://git-scm.com/downloads)
2. [Make](https://es.wikipedia.org/wiki/Make)
3. [Docker](https://www.docker.com/get-started)

### Building up the local environment

1. `make build` Create and start Docker containers with all the necessary dependencies.
2. `make doctrine-migrate-db` Update the persistence model

### Tests

1. `make u-test` Execute the unit tests
2. `make i-test` Execute the integration tests
3. `make a-test` Execute the acceptance suite tests
4. `make test` Restore the initial status of the database and run unit, integration and acceptance tests.

## Technical test summary

### Products list

Initial set of products in the database.

| id | sku | name | price | tax\_rate |
| :--- | :--- | :--- | :--- | :--- |
| eaccf910-eab2-42e8-9364-1eefcd65355c | 3472 | Beer - Rickards Red | 62.18 | 10 |
| 218d10cb-6d02-43f9-8b6e-a3722272d077 | 285 | Sloe Gin - Mcguinness | 69.15 | 21 |
| def54ed5-dce7-4985-991d-1de1824ae625 | 3021 | Bread - Dark Rye | 66.99 | 21 |
| 1f77bdc7-8690-4bd8-9fc2-c388b8116998 | 3977 | Appetizer - Crab And Brie | 33.96 | 4 |
| 277e3cd5-a04a-46ef-ab38-2d1c4ebf1f3f | 7840 | Cumin - Ground | 68.75 | 10 |
| e50272b3-365f-4438-9297-e2c74eb78333 | 9134 | Soup - Campbells | 82.55 | 0 |
| c619fc01-95ba-4596-b6dd-f0d729a628a7 | 3428 | Pasta - Orzo, Dry | 30.99 | 21 |
| 1b3a90ab-ca51-4a87-bb25-3d41d6994fb4 | 8144 | Towels - Paper / Kraft | 13.2 | 21 |
| 3425007e-a3d9-4639-8e8c-8ff4b2d90fd0 | 7911 | Blackberries | 21 | 21 |
| 8f9c5c4c-dd4c-46dd-8889-6b6b508d03e1 | 0057 | Coffee - Hazelnut Cream | 55.5 | 10 |

### Available API resources

| name | method | path |
|:-----|:-------|:-----|
| health-check_get | GET | /health-check |
| cart_create | PUT | /cart/{id} |
| cart_get | GET | /cart/{id} |
| cart_add_product | POST | /cart/{id}/products/ |
| cart_update_product | PATCH  | /cart/{id}/products/ |
| cart_delete_product | DELETE | /cart/{cartId}/product/{productId} |
| cart_pay | POST | /cart/{cartId}/pay |

### HTTP requests

A list of HTTP requests for testing the API resources of the technical test is available in the _./http_ project's folder.
