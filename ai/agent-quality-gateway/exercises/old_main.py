model: str = "qwen3.5-9b"
prompt: str = "Explain retry policy"
temperature: float = 0.2
max_tokens: int = 512

print(f"Model: {model}\n" +
    f"Prompt: {prompt}\n" +
    f"Temperature: {temperature}\n" +
    f"Max tokens: {max_tokens}")

print() # делаем отступ для следующего задания


def validate_temperature(temperature: float) -> bool:
    return 0.0 <= temperature <= 1.0

print(f"0.2: {validate_temperature(0.2)}\n" +
    f"1.0: {validate_temperature(1.0)}\n" +
    f"-0.1: {validate_temperature(-0.1)}\n" +
    f"1.5: {validate_temperature(1.5)}")

print() # делаем отступ для следующего задания

temperatures: list[float] = [0.2, 1.0, -0.1, 1.5]

for temperature in temperatures:
    is_valid = validate_temperature(temperature)
    print(f"{temperature}: {is_valid}")

print()  # делаем отступ для следующего задания

request = {
    "model": "qwen3.5-9b",
    "prompt": "Explain retry policy",
    "temperature": 0.2
}

for element in request:
    if element == "temperature":
        is_valid = validate_temperature(request[element])
        print(f"Temperature valid: {is_valid}")
    else:
        print(f"{element.title()}: {request[element]}")

request["temperature"] = 1.5
is_valid = validate_temperature(request["temperature"])
print(f"Temperature valid: {is_valid}")

print()  # делаем отступ для следующего задания

requests: list[dict] = [
    {
        "id": "req-001",
        "model": "qwen3.5-9b",
        "temperature": 0.2,
    },
    {
        "id": "req-002",
        "model": "qwen3.5-4b",
        "temperature": 1.5,
    },
    {
        "id": "req-003",
        "model": "qwen3.5-9b",
        "temperature": 0.7,
    },
]

for request in requests:
    is_valid = validate_temperature(request["temperature"])
    print(f"{request['id']}: {is_valid}")


