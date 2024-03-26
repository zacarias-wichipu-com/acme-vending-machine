## Acme Vending Machine

### Tools

1. [Git](https://git-scm.com/downloads)
2. [Make](https://es.wikipedia.org/wiki/Make)
3. [Docker](https://www.docker.com/get-started)

### Building up the local environment

Clone the repository.
```bash
git clone git@github.com:zacarias-wichipu-com/acme-vending-machine.git
```

Create and start Docker containers with all the necessary dependencies.
```bash
make build
```

### Tests

- Execute the unit tests
    ```bash
    make u-test
    ```
- Execute the integration tests
    ```bash
    make i-test
    ```
- Execute the application suite tests
    ```bash
    make a-test
    ```
- Restore the initial status of the database and run unit, integration and application tests.
    ```bash
    make test
    ```

### Vending machine CLI commands

- Initialise the vending machine
    ```bash
    make vm-init
    ```
- Print the vending machine state
    ```bash
    make vm-print
    ```
- Add a coin to the vending machine
    ```bash
    make vm-customer-add-coin
    ```
- Refunds all coins added to the vending machine
    ```bash
    make vm-customer-refund-coins
    ```
- Buy a product
    ```bash
    make vm-customer-buy-product
    ```
- Enables _service_ mode (maintenance)
    ```bash
    make vm-service-mode-enable
    ```
- Disables _service_ mode (maintenance)
    ```bash
    make vm-service-mode-disable
    ```

### To do
- Implement the use case to update the exchange coins in service mode.
- Implement the use case for updating products in service mode.
- Fix some corner cases in the handling of the return of coins after buy.
