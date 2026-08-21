from pydantic import BaseModel, Field


class AppRequest(BaseModel):
    prompt: str = Field(
        min_length=1,
        description="Field contain prompt for LLM",
        examples=["Посчитай 2 + 2"]
    )



class AppResponse(BaseModel):
    content: str = Field(
        description="Field contain LLM answer for client",
        examples=["Будет равно 4"]
    )


class ErrorResponse(BaseModel):
    error: str = Field(
        description="camelcase error name",
        examples=["example_error"]
    )
    message: str = Field(
        description="error message",
        examples=["example message!"]
    )