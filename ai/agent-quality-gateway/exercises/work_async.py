import asyncio


async def task_one() -> None:
    print("start")
    await asyncio.sleep(2)
    print("end")

async def task_two() -> None:
    print("start")
    await asyncio.sleep(2)
    print("end")

async def main() -> None:
    await asyncio.gather(
        task_one(),
        task_two(),
    )

asyncio.run(main())